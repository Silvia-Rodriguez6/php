<?php
require_once 'organizadorFruta.php';

$frutaManager = new organizadorFruta();
$frutas = $frutaManager->listarFrutas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listar Frutas</title>
</head>
<body>
    <h1>Listado de Frutas</h1>
    <?php if ($frutas): ?>
        <table border="1">
            <tr>
                <th>Nombre</th>
                <th>Precio (€)</th>
                <th>Temporada</th>
            </tr>
            <?php foreach ($frutas as $fruta): ?>
                <tr>
                    <td><?= htmlspecialchars($fruta['fruta']) ?></td>
                    <td><?= htmlspecialchars($fruta['precio']) ?></td>
                    <td><?= htmlspecialchars($fruta['temporada']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No hay frutas disponibles.</p>
    <?php endif; ?>
</body>
</html>
