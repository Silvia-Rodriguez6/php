<?php
require_once 'conexion.php'; 

$conexionBD = Conexion::obtenerConexion();

// Validar datos del formulario
$NombreFruta = filter_input(INPUT_POST, 'Nombre');
$Precio = filter_input(INPUT_POST, 'Precio', FILTER_VALIDATE_FLOAT);
$Temporada = filter_input(INPUT_POST, 'Temporada');

if ($NombreFruta && $Precio && $Temporada) {
    // Insertar en la base de datos
    $sql = "INSERT INTO precios (fruta, precio, temporada) VALUES ('$NombreFruta', $Precio, '$Temporada')";
    if ($conexionBD->SetConsulta($sql)) {
        echo "<p>Fruta añadida correctamente.</p>";
        echo "<a href='nueva_fruta.php'>Añadir otra fruta</a><br>";
        echo "<a href='listar_fruta.php'>Ver lista de frutas</a>";
    } else {
        echo "<p>Error al guardar la fruta. Por favor, inténtelo de nuevo.</p>";
    }
} else {
    echo "<p>Error en los datos del formulario. Por favor, revise los campos.</p>";
    echo "<a href='nueva_fruta.php'>Volver al formulario</a>";
}
?>
