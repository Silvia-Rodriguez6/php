<?php
require_once 'conexion.php'; // Incluir la clase de conexión

// Obtener la conexión
$conexionBD = Conexion::obtenerConexion();

// Consultar todos los productos
$sql = "SELECT fruta, precio, temporada FROM precios";
$Resultado = $conexionBD->SetConsulta($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Frutas</title>
</head>
<body>
    <h1>Listado de Productos en Venta</h1>
    <?php
    if ($Resultado) {
        echo "<table border='1'>";
        echo "<tr><th>Fruta</th><th>Precio (€)</th><th>Temporada</th></tr>";
        foreach ($Resultado as $fila) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fila['fruta']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['precio']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['temporada']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron productos en la base de datos.";
    }
    ?>
</body>
</html>
