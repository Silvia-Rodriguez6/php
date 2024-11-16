<?php
require_once 'conexion.php'; // Incluir la clase de conexión

// Obtener la conexión
$conexion = new Conexion("127.0.0.1", "mariadb", "mariadb", "mariadb");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro de Pacientes</title>
    <meta charset="UTF-8">
</head>
<body>
    <form action="index.php" method="POST">
        <p><label>DNI:</label> <input type="text" name="dni"></p>
        <p><label>Nombre:</label> <input type="text" name="nombre"></p>
        <p><label>Apellido:</label> <input type="text" name="apellido"></p>
        <p><label>Fecha de Nacimiento:</label> <input type="date" name="fecha_nacimiento"></p>
        <p><label>Sexo:</label> <input type="text" name="sexo"></p>
        <p><label>Diagnóstico:</label> <input type="text" name="diagnostico"></p>
        <button type="submit" name="registrar">Registrar Paciente</button>
        <button type="submit" name="actualizar_diagnostico">Actualizar Diagnóstico</button>
        <button type="submit" name="contar_sexo">Contar Pacientes por Sexo</button>
    </form>
</body>
<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['registrar'])) {
            $DNI = filter_input(INPUT_POST, 'dni');
            $Nombre = filter_input(INPUT_POST, 'nombre');
            $Apellido = filter_input(INPUT_POST, 'apellido');
            $Fecha_nacimiento = filter_input(INPUT_POST, 'fecha_nacimiento');
            $Sexo = filter_input(INPUT_POST, 'sexo');
            $Diagnostico = filter_input(INPUT_POST, 'diagnostico');
            if ($DNI && $Nombre && $Apellido && $Fecha_nacimiento && $Diagnostico) {
                $sql = "INSERT INTO pacientes (dni, nombre, apellido, fecha_nacimiento, sexo, diagnostico) 
                        VALUES ('$DNI', '$Nombre', '$Apellido', '$Fecha_nacimiento', '$Sexo', '$Diagnostico')";
                $conexion->SetConsulta($sql);
            } else {
                echo "Por favor, complete todos los campos.<br>";
            }
        }

        if (isset($_POST['actualizar_diagnostico'])) {
            $DNI = filter_input(INPUT_POST, 'dni');
            $Nuevo_diagnostico = filter_input(INPUT_POST, 'diagnostico');
        
            if ($DNI && $Nuevo_diagnostico) {
                $sql = "UPDATE pacientes SET diagnostico = '$Nuevo_diagnostico' WHERE dni = '$DNI'";
                if ($conexion->SetConsulta($sql)) {
                    echo "El diagnostico del paciente con dni '$DNI' ha sido actualizado a '$Nuevo_diagnostico'.<br>";
                } else {
                    echo "Error al actualizar el diagnostico. Por favor, verifica los datos.<br>";
                }
            } else {
                echo "Por favor, introduce un DNI del paciente válido.<br>";
            }
        }

        if (isset($_POST['contar_sexo'])) {
            // Consulta para contar la cantidad total de pacientes masculinos y femeninos
            $sql = "SELECT sexo, COUNT(*) AS total_cantidad 
                    FROM pacientes
                    GROUP BY sexo";
            $Resultado = $conexion->SetConsulta($sql);
        
            if ($Resultado) {
                foreach ($Resultado as $fila) {
                    echo "Sexo: " . htmlspecialchars($fila['sexo']) . "<br>";
                    echo "Cantidad total de pacientes: " . htmlspecialchars($fila['total_cantidad']) . "<br><br>";
                }
            } else {
                echo "No se encontraron pacientes en la base de datos.<br>";
            }
        }
        
    }

    $conexion->cerrarConexion();
    ?>

</html>