<?php
require_once 'conexion.php'; // Incluir la clase de conexión

// Obtener la conexión
$conexion = new Conexion("127.0.0.1", "mariadb", "mariadb", "mariadb");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Productos</title>
    <meta charset="UTF-8">
</head>
<body>
    <form action="index.php" method="POST">
        <p><label>Código Producto:</label> <input type="text" name="codigo_producto"></p>
        <p><label>Nombre:</label> <input type="text" name="nombre"></p>
        <p><label>Categoría:</label> <input type="text" name="categoria"></p>
        <p><label>Cantidad:</label> <input type="number" name="cantidad"></p>
        <p><label>Precio:</label> <input type="text" name="precio"></p>
        <button type="submit" name="agregar">Agregar Producto</button>
        <button type="submit" name="actualizar_precio">Actualizar Precio</button>
        <button type="submit" name="consultar_total">Consultar Total por Categoría</button>
    </form>
</body>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['agregar'])) {
            $Codigo_Producto = filter_input(INPUT_POST, 'codigo_producto');
            $Nombre = filter_input(INPUT_POST, 'nombre');
            $Categoria = filter_input(INPUT_POST, 'categoria');
            $Cantidad = filter_input(INPUT_POST, 'cantidad');
            $Precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
            if ($Codigo_Producto && $Nombre && $Categoria && $Cantidad && $Precio) {
                $sql = "INSERT INTO productos (codigo_producto, nombre, categoria, cantidad, precios) 
                        VALUES ('$Codigo_Producto', '$Nombre', '$Categoria', '$Cantidad', '$Precio')";
                $conexion->SetConsulta($sql);
            } else {
                echo "Por favor, complete todos los campos.<br>";
            }
        }

       
        if (isset($_POST['actualizar_precio'])) {
            $Codigo_Producto = filter_input(INPUT_POST, 'codigo_producto');
            $Nuevo_Precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
        
            if ($Codigo_Producto && $Nuevo_Precio) {
                $sql = "UPDATE productos SET precios = $Nuevo_Precio WHERE codigo_producto = '$Codigo_Producto'";
                if ($conexion->SetConsulta($sql)) {
                    echo "El precio del producto con código '$Codigo_Producto' ha sido actualizado a $Nuevo_Precio.<br>";
                } else {
                    echo "Error al actualizar el precio. Por favor, verifica los datos.<br>";
                }
            } else {
                echo "Por favor, introduce un código de producto válido y un precio.<br>";
            }
        }

        if (isset($_POST['consultar_total'])) {
            $Categoria = filter_input(INPUT_POST, 'categoria');
            if ($Categoria) {
                // Consulta para obtener la suma de cantidades y el valor total
                $sql = "SELECT categoria, SUM(cantidad) AS total_cantidad, SUM(cantidad * precios) AS total_valor 
                        FROM productos 
                        WHERE categoria = '$Categoria'
                        GROUP BY categoria";
                $Resultado = $conexion->SetConsulta($sql);
                if ($Resultado) {
                    foreach ($Resultado as $fila) {
                        echo "Categoría: " . htmlspecialchars($fila['categoria']) . "<br>";
                        echo "Cantidad total: " . htmlspecialchars($fila['total_cantidad']) . "<br>";
                        echo "Valor total: €" . number_format($fila['total_valor'], 2) . "<br><br>";
                    }
                } else {
                    echo "No se encontraron productos en la categoría '$Categoria'.<br>";
                }
            } else {
                echo "Por favor, ingrese una categoría válida.<br>";
            }
        }
    }

    $conexion->cerrarConexion();
    ?>
</body>
</html>
