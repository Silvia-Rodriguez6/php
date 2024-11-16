<?php
require_once 'FrutaManager.php';

$frutaManager = new organizadorFruta();

// Validar el ID
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    if ($frutaManager->borrarFruta($id)) {
        echo "<p>Fruta eliminada correctamente.</p>";
    } else {
        echo "<p>Error al eliminar la fruta.</p>";
    }
} else {
    echo "<p>ID inválido.</p>";
}
?>
