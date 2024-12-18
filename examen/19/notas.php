<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion_bd = @mysqli_connect("127.0.0.1", "mariadb","mariadb", "mariadb");
    
    if (!$conexion_bd) {
        die("Error al conectar con la base de datos: " . mysqli_connect_error());
    }

    // Obtener los datos del formulario
    $dni = $_POST['dni'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $grupo = $_POST['grupo'] ?? '';
    $fecha_hora = $_POST['fecha_hora'] ?? '';
    $asignatura = $_POST['asignatura'] ?? '';
    $nota = isset($_POST['nota']) ? floatval($_POST['nota']) : null;

    // Alta de una nueva nota
    if (isset($_POST['nueva'])) {
        if ($dni && $nombre && $grupo && $fecha_hora && $asignatura && $nota !== null) {
            $sql = "INSERT INTO notas (dni, nombre, grupo, fecha_hora, asignatura, nota) 
                    VALUES ('$dni', '$nombre', '$grupo', '$fecha_hora', '$asignatura', $nota)";
            
            if (mysqli_query($conexion_bd, $sql)) {
                echo "Nota guardada correctamente.";
            } else {
                echo "Error al guardar la nota: " . mysqli_error($conexion_bd);
            }
        } else {
            echo "Por favor, complete todos los campos.";
        }
    }

    // Calcular la nota media
    if (isset($_POST['media'])) {
        if ($dni && $asignatura) {
            $sql = "SELECT AVG(nota) as nota_media FROM notas WHERE dni = '$dni' AND asignatura = '$asignatura'";
            $resultado = mysqli_query($conexion_bd, $sql);

            if ($resultado && $fila = mysqli_fetch_assoc($resultado)) {
                if ($fila['nota_media'] !== null) {
                    echo "La nota media de $nombre en $asignatura es: " . $fila['nota_media'];
                } else {
                    echo "No se encontraron notas para el DNI y asignatura proporcionados.";
                }
            } else {
                echo "Error en la consulta: " . mysqli_error($conexion_bd);
            }
        } else {
            echo "Por favor, proporcione el DNI y la asignatura.";
        }
    }

    // Mostrar todos los usuarios
    if (isset($_POST['mostrar'])) {
        $sql = "SELECT DISTINCT dni, nombre, grupo FROM notas";
        $resultado = mysqli_query($conexion_bd, $sql);

        if ($resultado) {
            echo "<h2>Lista de Usuarios Registrados</h2>";
            echo "<table border='1'>
                    <tr>
                        <th>DNI</th>
                        <th>Nombre</th>
                        <th>Grupo</th>
                    </tr>";
            while ($usuario = mysqli_fetch_assoc($resultado)) {
                echo "<tr>
                        <td>{$usuario['dni']}</td>
                        <td>{$usuario['nombre']}</td>
                        <td>{$usuario['grupo']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron usuarios registrados.";
        }
    }

    mysqli_close($conexion_bd);
}
?>
