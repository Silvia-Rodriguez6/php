<?php
require_once 'organizadorFruta.php';

$frutaManager = new organizadorFruta();

// Validar datos del formulario
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nuevoPrecio = filter_input(INPUT_POST, 'nuevo_precio', FILTER_VALIDATE_FLOAT);

if ($id && $nuevoPrecio) {
    if ($frutaManager->editarFruta($id, $nuevoPrecio)) {
        echo "<p>Precio actualizado correctamente.</p>";
    } else {
        echo "<p>Error al actualizar el precio.</p>";
    }
} else {
    echo "<p>Datos inválidos.</p>";
}
?>
