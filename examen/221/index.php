<?php
require_once 'conexion.php'; // Incluir la clase de conexión

// Obtener la conexión
$conexion = new Conexion("127.0.0.1", "mariadb", "mariadb", "mariadb");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Transferencias</title>
    <meta charset="UTF-8">
</head>
<body>
    <div>
        <form action="index.php" method="POST">
            <p><label>Sujeto:</label><input type="text" name="sujeto"></p>
            <p><label>Código de Transferencia:</label><input type="text" name="codtransfer"></p>
            <p><label>Cantidad:</label><input type="text" name="cantidad"></p>
            <p><label>Fecha y Hora (YYYY-MM-DDThh:mm):</label><input type="datetime-local" name="fecha_hora"></p>
            <div class="form-group">
                <button type="submit" name="nueva" class="btn btn-primary">Nueva</button>
                <button type="submit" name="reclamar" class="btn btn-secondary">Reclamar</button>
                <button type="submit" name="anular_reclamar" class="btn btn-secondary">Anular Reclamo</button>
                <button type="submit" name="recibidas" class="btn btn-secondary">Recibidas</button>
                <button type="reset" name="cancelar" class="btn btn-danger">Cancelar</button>
                <button type="submit" name="guardar_txt" class="btn btn-secondary">Guardar en Archivo</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php
function RellenaRegistro ($Datos){

    $Archivo = fopen("registro.txt" ,"a+");   

    fwrite($Archivo, $Datos);
    fclose($Archivo);
}


function LeerRegistro() {
    // Abrir el archivo listado.txt para lectura
    $archivo = fopen("listado.txt", "r");

    // Validar si el archivo se pudo abrir
    if (!$archivo) {
        echo "No se pudo abrir el archivo 'listado.txt'.<br>";
        return [];
    }

    // Leer el archivo completo
    $archivoLeido = fread($archivo, filesize("listado.txt"));
    fclose($archivo); // Cerrar el archivo después de leer

    // Separar el contenido por líneas
    $transferencias = explode("\n", $archivoLeido);

    return $transferencias; // Devolver las transferencias como un array
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nueva'])) {
        $Sujeto = filter_input(INPUT_POST, 'sujeto');
        $Codtransfer = filter_input(INPUT_POST, 'codtransfer');
        $Cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_FLOAT);
        $Fecha_hora = filter_input(INPUT_POST, 'fecha_hora');
    
        if ($Cantidad === false || $Cantidad === null) {
            $Cantidad = 0; // Si no es válido, asignar un valor predeterminado
        }
    
        $sql = "INSERT INTO transfer (sujeto, codtransfer, cantidad, fecha_hora) 
                VALUES ('$Sujeto', '$Codtransfer', '$Cantidad', '$Fecha_hora')";
        $conexion->SetConsulta($sql);
    
        $Datos = "$Sujeto $Codtransfer $Cantidad $Fecha_hora\n";
        RellenaRegistro($Datos);
    
        echo "Nueva transferencia guardada en la base de datos y en registro.txt.<br>";
    }
    
            
        if (isset($_POST['guardar_txt'])) {
    // Leer el archivo listado.txt y guardar sus transferencias en la base de datos
    $transferencias = LeerRegistro();

    if (!empty($transferencias)) {
        foreach ($transferencias as $linea) {
            $datos = explode(" ", trim($linea));

            // Validar que la línea tenga al menos 4 campos
            if (count($datos) < 4) {
                continue; // Saltar líneas mal formateadas
            }

            $sujeto = $datos[0];
            $codtransfer = $datos[1];
            $cantidad = $datos[2];
            $fecha_hora = $datos[3];

            // Guardar en la base de datos
            $sql = "INSERT INTO transfer (sujeto, codtransfer, cantidad, fecha_hora) 
                    VALUES ('$sujeto', '$codtransfer', '$cantidad', '$fecha_hora')";
            $conexion->SetConsulta($sql);
        }

        echo "Transferencias del archivo listado.txt procesadas y guardadas en la base de datos.<br>";
    } else {
        echo "El archivo listado.txt está vacío o no contiene datos válidos.<br>";
    }
}




    // Reclamar transferencia
    if (isset($_POST['reclamar'])) {
        $Codtransfer = $_POST['codtransfer'];
        if ($Codtransfer) {
            $sql = "UPDATE transfer SET reclamar = TRUE WHERE codtransfer = '$Codtransfer'";

    
            $resultado = $conexion->SetConsulta($sql);
            if ($resultado) {
                echo "Transferencia reclamada correctamente.<br>";
            } else {
                echo "Error al reclamar la transferencia.<br>";
            }
        } else {
            echo "Debe proporcionar un código de transferencia.<br>";
        }
    }
    
    // Anular reclamo de transferencia
    if (isset($_POST['anular_reclamar'])) {
        $Codtransfer = $_POST['codtransfer'];
        if ($Codtransfer) {
            $sql = "UPDATE transfer SET reclamar = FALSE WHERE codtransfer = '$Codtransfer'";
            $resultado = $conexion->SetConsulta($sql);
            if ($resultado) {
                echo "Reclamo de la transferencia anulado correctamente.<br>";
            } else {
                echo "Error al intentar anular el reclamo de la transferencia.<br>";
            }
        } else {
            echo "Debe proporcionar un código de transferencia.<br>";
        }
    }

    // Mostrar transferencias recibidas y su suma total
    if (isset($_POST['recibidas'])) {
        $sql = "SELECT * FROM transfer WHERE cantidad > 0";
        $resultado = $conexion->SetConsulta($sql);
        if ($resultado) {
            $sumaRecibidas = 0;
            echo "<h2>Transferencias Recibidas</h2>";
            foreach ($resultado as $transferencia) {
                $sumaRecibidas += $transferencia['cantidad'];
                echo "Código: {$transferencia['codtransfer']}, Cantidad: {$transferencia['cantidad']}, Fecha: {$transferencia['fecha_hora']}, Sujeto: {$transferencia['sujeto']}<br>";
            }
            echo "<strong>Suma Total Recibidas: " . $sumaRecibidas . "</strong><br>";
        } else {
            echo "Error al obtener las transferencias recibidas.<br>";
        }
    }
}
?>
