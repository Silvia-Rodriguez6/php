<?php
function obtenerConexion() {
    // Obtener las variables de entorno correctas
    $host = '127.0.0.1';          
    $user = 'mariadb';          
    $password = 'mariadb';   
    $dbname = 'mariadb';     

    // Crear una conexión con MariaDB utilizando mysqli
    $conexion = new mysqli($host, $user, $password, $dbname);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    return $conexion;
}
?>