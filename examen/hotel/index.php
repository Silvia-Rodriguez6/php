<?php
require_once 'conexion.php'; // Incluir la clase de conexión

// Obtener la conexión
$conexion = new Conexion("127.0.0.1", "mariadb", "mariadb", "mariadb");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Reservas</title>
    <meta charset="UTF-8">
</head>
<body>
    <form action="index.php" method="POST">
        <p><label>Código de Reserva:</label> <input type="text" name="codigo_reserva"></p>
        <p><label>Nombre del Cliente:</label> <input type="text" name="nombre_cliente"></p>
        <p><label>Habitación:</label> <input type="number" name="habitacion"></p>
        <p><label>Fecha de Inicio:</label> <input type="date" name="fecha_inicio"></p>
        <p><label>Fecha de Fin:</label> <input type="date" name="fecha_fin"></p>
        <p><label>Total a Pagar:</label> <input type="text" name="total_pago"></p>
        <button type="submit" name="crear_reserva">Crear Reserva</button>
        <button type="submit" name="consultar_cliente">Consultar Reservas por Cliente</button>
        <button type="submit" name="calcular_ingresos">Calcular Ingresos por Periodo</button>
    </form>
</body>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['crear_reserva'])) {
            $CodigoReserva = filter_input(INPUT_POST, 'codigo_reserva');
            $NombreCliente = filter_input(INPUT_POST, 'nombre_cliente');
            $Habitacion = filter_input(INPUT_POST, 'habitacion', FILTER_VALIDATE_INT);
            $FechaIncio = filter_input(INPUT_POST, 'fecha_inicio');
            $FechaFin = filter_input(INPUT_POST, 'fecha_fin');
            $TotalPago = filter_input(INPUT_POST, 'total_pago', FILTER_VALIDATE_FLOAT);
            if ($CodigoReserva && $NombreCliente && $Habitacion && $FechaIncio && $FechaFin && $TotalPago) {
                $sql = "INSERT INTO reservas (codigo_reserva, nombre_cliente, habitacion, fecha_inicio, fecha_fin, total_pago) 
                        VALUES ('$CodigoReserva', '$NombreCliente', '$Habitacion', '$FechaIncio', '$FechaFin', '$TotalPago')";
                $conexion->SetConsulta($sql);
            } else {
                echo "Por favor, complete todos los campos.<br>";
            }
        }

        if (isset($_POST['consultar_cliente'])) {
            $NombreCliente = filter_input(INPUT_POST, 'nombre_cliente');
        
            if ($NombreCliente) {
                // Consulta para obtener las reservas del cliente
                $sql = "SELECT * FROM reservas WHERE nombre_cliente = '$NombreCliente'";
        
                // Ejecutar consulta
                $resultado = $conexion->SetConsulta($sql);
        
                // Verificar y mostrar resultados
                if ($resultado) {
                    echo "<h3>Reservas para el cliente: $NombreCliente</h3>";
        
                    
                    foreach ($resultado as $reserva) {
                        echo "<p><strong>Código Reserva:</strong> {$reserva['codigo_reserva']}</p>";
                        echo "<p><strong>Nombre Cliente:</strong> {$reserva['nombre_cliente']}</p>";
                        echo "<p><strong>Habitación:</strong> {$reserva['habitacion']}</p>";
                        echo "<p><strong>Fecha Inicio:</strong> {$reserva['fecha_inicio']}</p>";
                        echo "<p><strong>Fecha Fin:</strong> {$reserva['fecha_fin']}</p>";
                        echo "<p><strong>Total:</strong> {$reserva['total_pago']}</p>";
                        echo "<hr>";
                    }
                } else {
                    echo "<p>No se encontraron reservas para el cliente <strong>$NombreCliente</strong>.</p>";
                }
            } else {
                echo "<p>Por favor, introduce un nombre válido.</p>";
            }
        }
        

        if (isset($_POST['calcular_ingresos'])) {
            $FechaInicio = filter_input(INPUT_POST, 'fecha_inicio');
            $FechaFin = filter_input(INPUT_POST, 'fecha_fin');
        
            if ($FechaInicio && $FechaFin) {
                $sql = "SELECT SUM(total_pago) AS ingresos_totales FROM reservas 
                        WHERE fecha_inicio >= '$FechaInicio' AND fecha_fin <= '$FechaFin'";
                $Resultado = $conexion->SetConsulta($sql);
        
                if ($Resultado) {
                    $IngresosTotales = $Resultado[0]['ingresos_totales'] ?? 0;
                    echo "Ingresos totales del período ($FechaInicio a $FechaFin) € $IngresosTotales";
                } else {
                    echo "No se encontraron reservas en el período especificado.";
                }
            } else {
                echo "Por favor, selecciona un rango de fechas válido.";
            }
        }
        
    }

    $conexion->cerrarConexion();
    ?>


</html>