<?php
require_once 'conexion.php'; // Incluir la clase de conexión

// Obtener la conexión
$conexionBD = Conexion::obtenerConexion();

// Validar datos del formulario
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nuevo_precio = filter_input(INPUT_POST, 'nuevo_precio', FILTER_VALIDATE_FLOAT);

if ($id && $nuevo_precio) {
    // Actualizar precio
    $sql = "UPDATE precios SET precio = $nuevo_precio WHERE id = $id";
    if ($conexionBD->SetConsulta($sql)) {
        echo "<p>Precio actualizado correctamente.</p>";
    } else {
        echo "<p>Error al actualizar el precio.</p>";
    }
} else {
    echo "<p>Datos inválidos. Por favor, inténtelo de nuevo.</p>";
}

echo "<a href='editar_fruta.php'>Editar otra fruta</a><br>";
echo "<a href='listar_fruta.php'>Ver lista de frutas</a>";
?>
