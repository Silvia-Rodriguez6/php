<?php
function validarUsuario($usuario, $contraseña){

$usuarios = array("silvia" => "1234",
"Luisa" => "baobab",
"Antonio" => "olmo");

 return array_key_exists($usuario, $usuarios) && $usuarios[$usuario] === $contraseña;
}

?>