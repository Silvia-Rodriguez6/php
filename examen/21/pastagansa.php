<?php

include 'conexion.php';

// Manejo de formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = conectarBD();

    // Agregar nueva transferencia
    if (isset($_POST['nueva'])) {
        $sujeto = $_POST['sujeto'];
        $codtransfer = $_POST['codtransfer'];
        $cantidad = $_POST['cantidad'];
        $fecha_hora = $_POST['fecha_hora'];

        $stmt = $conexion->prepare("INSERT INTO transfer (codtransfer, cantidad, fecha_hora, sujeto) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $codtransfer, $cantidad, $fecha_hora, $sujeto);
        if ($stmt->execute()) {
            echo "Transferencia agregada correctamente.";
        } else {
            echo "Error al agregar la transferencia.";
        }
    }

    // Reclamar transferencia
    if (isset($_POST['reclamar'])) {
        $codtransfer = $_POST['codtransfer'];

        $stmt = $conexion->prepare("UPDATE transfer SET reclamar = TRUE WHERE codtransfer = ?");
        $stmt->bind_param("s", $codtransfer);
        if ($stmt->execute()) {
            echo "Transferencia reclamada correctamente.";
        } else {
            echo "Error al reclamar la transferencia.";
        }
    }

    // Anular reclamo de transferencia
    if (isset($_POST['anular_reclamar'])) {
        $codtransfer = $_POST['codtransfer'];

        $stmt = $conexion->prepare("UPDATE transfer SET reclamar = FALSE WHERE codtransfer = ?");
        $stmt->bind_param("s", $codtransfer);
        if ($stmt->execute()) {
            echo "Reclamo de la transferencia anulado correctamente.";
        } else {
            echo "Error al intentar anular el reclamo de la transferencia.";
        }
    }

    // Mostrar transferencias recibidas y su suma total
    if (isset($_POST['recibidas'])) {
        $stmt = $conexion->query("SELECT * FROM transfer WHERE cantidad > 0");
        if ($stmt) {
            $sumaRecibidas = 0;
            echo "<h2>Transferencias Recibidas</h2>";
            while ($transferencia = $stmt->fetch_assoc()) {
                $sumaRecibidas += $transferencia['cantidad'];
                echo "Código: {$transferencia['codtransfer']}, Cantidad: {$transferencia['cantidad']}, Fecha: {$transferencia['fecha_hora']}, Sujeto: {$transferencia['sujeto']}<br>";
            }
            echo "<strong>Suma Total Recibidas: " . $sumaRecibidas . "</strong>";
        } else {
            echo "Error al obtener las transferencias recibidas.";
        }
    }
}
?>