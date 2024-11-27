<?php
require_once 'conexion.php'; // Incluir la clase de conexión

// Obtener la conexión
$conexion = new Conexion("127.0.0.1", "mariadb", "mariadb", "mariadb");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Notas</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h2>Registro de Notas de Exámenes</h2>
    <div>
        <form action="index.php" method="POST">
            <p><label>Nombre:</label> <input type="text" name="nombre"></p>
            <p><label>DNI:</label> <input type="text" name="dni"></p>
            <p><label>Grupo:</label> <input type="text" name="grupo"></p>
            <p><label>Fecha y Hora:</label> <input type="datetime-local" name="fecha_hora"></p>
            <p><label>Asignatura:</label> <input type="text" name="asignatura"></p>
            <p><label>Nota:</label> <input type="text" name="nota"></p>
            <div>
                <button type="submit" name="nueva">Nueva</button>
                <button type="submit" name="media">Nota Media</button>
                <button type="submit" name="mostrar">Mostrar Usuarios</button>
                <button type="submit" name="eliminar">Eliminar Usuarios</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['nueva'])) {
            $DNI = filter_input(INPUT_POST, 'dni');
            $Nombre = filter_input(INPUT_POST, 'nombre');
            $Grupo = filter_input(INPUT_POST, 'grupo');
            $Fecha_hora = filter_input(INPUT_POST, 'fecha_hora');
            $Asignatura = filter_input(INPUT_POST, 'asignatura');
            $Nota = filter_input(INPUT_POST, 'nota');
            if ($DNI && $Nombre && $Grupo && $Fecha_hora && $Asignatura && $Nota) {
                $sql = "INSERT INTO notas (dni, nombre, grupo, fecha_hora, asignatura, nota) 
                        VALUES ('$DNI', '$Nombre', '$Grupo', '$Fecha_hora', '$Asignatura', '$Nota')";
                $conexion->SetConsulta($sql);
            } else {
                echo "Por favor, complete todos los campos.<br>";
            }
        }

        if (isset($_POST['media'])) {
            $DNI = filter_input(INPUT_POST, 'dni');
            $Asignatura = filter_input(INPUT_POST, 'asignatura');
        
            if ($DNI && $Asignatura) {
                // Consulta para calcular la nota media
                $sql = "SELECT AVG(nota) AS nota_media FROM notas WHERE dni = '$DNI' AND asignatura = '$Asignatura'";
                $Resultado = $conexion->SetConsulta($sql);
        
                if ($Resultado) {
                    // Obtener la nota media del resultado
                    $NotaMedia = $Resultado[0]['nota_media'] ?? null;
        
                    if ($NotaMedia !== null) {
                        echo "La nota media del DNI '$DNI' en la asignatura '$Asignatura' es: $NotaMedia.<br>";
                    } else {
                        echo "No se encontraron notas para el DNI '$DNI' en la asignatura '$Asignatura'.<br>";
                    }
                } else {
                    echo "Error al calcular la nota media. Por favor, verifica los datos.<br>";
                }
            } else {
                echo "Por favor, introduce un DNI y una asignatura válidos.<br>";
            }
        }
        

        if (isset($_POST['mostrar'])) {
            // Consulta para obtener todos los usuarios únicos
            $sql = "SELECT DISTINCT dni, nombre, grupo FROM notas";
            $Resultado = $conexion->SetConsulta($sql);
        
            if ($Resultado) {
                echo "<h3>Listado de Usuarios:</h3>";
                echo "<table border='1' cellpadding='5' cellspacing='0'>";
                echo "<tr><th>DNI</th><th>Nombre</th><th>Grupo</th></tr>";
        
                foreach ($Resultado as $fila) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($fila['dni']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['grupo']) . "</td>";
                    echo "</tr>";
                }
        
                echo "</table>";
            } else {
                echo "<p>No se encontraron usuarios en la base de datos.</p>";
            }
        }  
        if (isset($_POST['eliminar'])) {
            $DNI = filter_input(INPUT_POST, 'dni');
        
            if ($DNI) {
                $sql = "DELETE FROM notas WHERE dni = '$DNI'";
                $Resultado = $conexion->SetConsulta($sql);
        
                if ($Resultado) {
                    echo "<p>El usuario con DNI '$DNI' ha sido eliminado correctamente.</p>";
                } else {
                    echo "<p>Error al eliminar el usuario. Por favor, verifica los datos e inténtalo nuevamente.</p>";
                }
            } else {
                echo "<p>Por favor, introduce un DNI válido para eliminar.</p>";
            }
        }
        
        
    }

    $conexion->cerrarConexion();
    ?>

</html>