<<?php
require_once 'conexion.php'; // Incluir la clase de conexión


// Obtener la conexión
$conexionBD = Conexion::obtenerConexion();

// Verificar conexión y existencia de la tabla
echo "Conexión exitosa a la base de datos.<br>";
$sql = "SHOW TABLES LIKE 'precios1'";
$result = $conexionBD->SetConsulta($sql);

if ($result) {
    echo "La tabla 'precios1' existe en la base de datos.<br>";
} else {
    die("La tabla 'precios1' NO existe en la base de datos. Asegúrate de crearla antes de continuar.");
}
// Obtener la conexión
$conexionBD = Conexion::obtenerConexion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Frutería</title>
</head>
<body>
    <h1>Formulario de Frutería</h1>
    <form action="index.php" method="post">
        <p>
            <label for="TextNombre">Nombre:</label>
            <input type="text" name="Nombre" id="TextNombre" placeholder="Nombre">
        </p>
        <p>
            <label for="TextPrecio">Precio:</label>
            <input type="text" name="Precio" id="TextPrecio" placeholder="€/Kg">
        </p>
        <p>
            <label for="TextTemporada">Temporada:</label>
            <input type="text" name="Temporada" id="TextTemporada" placeholder="Estación del año">
        </p>
        <input type="submit" name="Crear" value="Crear">
        <input type="submit" name="FrutasOrdenadas" value="Ver Frutas Ordenadas">
        <input type="submit" name="filtroA" value="Frutas más de 1.5 €/Kg">
        <input type="submit" name="filtroB" value="Frutas de Otoño">
        <input type="submit" name="filtroC" value="Frutas Otoño o Anual">
        <input type="submit" name="filtroD" value="Frutas anuales y menos de 0.5 €/Kg">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['Crear'])) {
            $NombreFruta = filter_input(INPUT_POST, 'Nombre');
            $Precio = filter_input(INPUT_POST, 'Precio', FILTER_VALIDATE_FLOAT);
            $Temporada = filter_input(INPUT_POST, 'Temporada');
            if ($NombreFruta && $Precio && $Temporada) {
                $sql = "INSERT INTO precios1 (fruta, precio, temporada) VALUES ('$NombreFruta', '$Precio', '$Temporada')";
                $conexionBD->SetConsulta($sql);
            } else {
                echo "Por favor, complete todos los campos.<br>";
            }
        }

        if (isset($_POST['FrutasOrdenadas'])) {
            $sql = "SELECT fruta, precio FROM precios1 ORDER BY precio ASC";
            $Resultado = $conexionBD->SetConsulta($sql);
            if ($Resultado) {
                foreach ($Resultado as $fila) {
                    echo "Fruta: " . htmlspecialchars($fila['fruta']) . "<br>";
                    echo "Precio: " . htmlspecialchars($fila['precio']) . " €/Kg<br><br>";
                }
            } else {
                echo "No se encontraron resultados.";
            }
        }

        if (isset($_POST['filtroA'])) {
            $sql = "SELECT fruta, precio FROM precios1 WHERE precio > 1.5";
            $Resultado = $conexionBD->SetConsulta($sql);
            if ($Resultado) {
                foreach ($Resultado as $fila) {
                    echo "Fruta: " . htmlspecialchars($fila['fruta']) . "<br>";
                    echo "Precio: " . htmlspecialchars($fila['precio']) . " €/Kg<br><br>";
                }
            } else {
                echo "No se encontraron resultados.";
            }
        }
    }

    if (isset($_POST['filtroB'])) {
        $sql = "SELECT fruta, precio, temporada FROM precios1 WHERE temporada = 'otoño'";
        $Resultado = $conexionBD->SetConsulta($sql);
        if ($Resultado) {
            foreach ($Resultado as $fila) {
                echo "Fruta: " . htmlspecialchars($fila['fruta']) . "<br>";
                echo "Precio: " . htmlspecialchars($fila['precio']) . "<br><br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }

    if (isset($_POST['filtroC'])) {
        $sql = "SELECT fruta, precio, temporada FROM precios1 WHERE temporada = 'otoño' OR temporada = 'anual'";
        $Resultado = $conexionBD->SetConsulta($sql);
        if ($Resultado) {
            foreach ($Resultado as $fila) {
                echo "Fruta: " . htmlspecialchars($fila['fruta']) . "<br>";
                echo "Precio: " . htmlspecialchars($fila['precio']) . "<br><br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }

    if (isset($_POST['filtroD'])) {
        $sql = "SELECT fruta, precio, temporada FROM precios1 WHERE precio < 0.5 AND temporada = 'anual'";
        $Resultado = $conexionBD->SetConsulta($sql);
        if ($Resultado) {
            foreach ($Resultado as $fila) {
                echo "Fruta: " . htmlspecialchars($fila['fruta']) . "<br>";
                echo "Precio: " . htmlspecialchars($fila['precio']) . "<br><br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }

    $conexionBD->cerrarBD();
    ?>
</body>
</html>
