<!DOCTYPE html>
<html>
    <head>
        <title>Gestión de Productos</title>
        <meta charset = "UTF-8">
</head>
<body>
    <form action = "productos.php" method = "POST">
        <p><label>Código Producto: </label><input type="text" name = "codigo_producto"></p>
        <p><label>Nombre: </label><input type="text" name = "nombre"></p>
        <p><label>Categoria: </label><input type="text" name = "categoria"></p>
        <p><label>Cantidad: </label><input type="number" name = "cantidad"></p>
        <p><label>Precio: </label><input type="text" name = "precio"></p>
        <button type = "submit" name = "agregar">Agregar Producto</button>
        <button type = "submit" name = "actualizar_precio">Actualizar Precio</button>
        <button type = "submit" name = "consultar_total">Consultar Total por Categoría</button>
    </form>
</body>
</html>