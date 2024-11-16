<?php
require_once 'conexion.php'; 

$conexionBD = Conexion::obtenerConexion();

// Consultar todas las frutas
$sql = "SELECT id, fruta, precio FROM precios";
$frutas = $conexionBD->SetConsulta($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Fruta</title>
</head>
<body>
    <h1>Editar Precio de una Fruta</h1>
    <?php if ($frutas): ?>
        <form action="mod_fruta.php" method="post">
            <label for="fruta">Selecciona la fruta:</label>
            <select name="id" id="fruta" required>
                <?php foreach ($frutas as $fruta): ?>
                    <option value="<?= $fruta['id'] ?>">
                        <?= htmlspecialchars($fruta['fruta']) ?> - <?= htmlspecialchars($fruta['precio']) ?> €/Kg
                    </option>
                <?php endforeach; ?>
            </select>
            <p>
                <label for="nuevo_precio">Nuevo Precio (€):</label>
                <input type="text" name="nuevo_precio" id="nuevo_precio" placeholder="Ejemplo: 2.5" required>
            </p>
            <p>
                <input type="submit" value="Actualizar Precio">
            </p>
        </form>
    <?php else: ?>
        <p>No hay frutas en el catálogo.</p>
    <?php endif; ?>
</body>
</html>
