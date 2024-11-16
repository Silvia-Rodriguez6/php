<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Nueva Fruta</title>
</head>
<body>
    <h1>Añadir Nueva Fruta</h1>
    <form action="guardar_fruta.php" method="post">
        <p>
            <label for="TextNombre">Nombre de la fruta:</label>
            <input type="text" name="Nombre" id="TextNombre" placeholder="Ejemplo: Manzana" required>
        </p>
        <p>
            <label for="TextPrecio">Precio (€/Kg):</label>
            <input type="text" name="Precio" id="TextPrecio" placeholder="Ejemplo: 2.5" required>
        </p>
        <p>
            <label for="TextTemporada">Temporada:</label>
            <input type="text" name="Temporada" id="TextTemporada" placeholder="Ejemplo: Verano" required>
        </p>
        <p>
            <input type="submit" value="Guardar Fruta">
        </p>
    </form>
</body>
</html>
