<?php
require_once 'conexion.php'; // Incluir la clase de conexión

class organizadorFruta {
    private $conexion;

    public function __construct() {
        // Obtener la conexión a la base de datos
        $this->conexion = Conexion::obtenerConexion();
    }

    // Listar todas las frutas
    public function listarFrutas() {
        $sql = "SELECT id, fruta, precio, temporada FROM precios1";
        return $this->conexion->SetConsulta($sql);
    }

    // Añadir una nueva fruta
    public function añadirFruta($nombre, $precio, $temporada) {
        $sql = "INSERT INTO precios1 (fruta, precio, temporada) VALUES ('$nombre', $precio, '$temporada')";
        return $this->conexion->SetConsulta($sql);
    }

    // Borrar una fruta por ID
    public function borrarFruta($id) {
        $sql = "DELETE FROM precios1 WHERE id = $id";
        return $this->conexion->SetConsulta($sql);
    }

    // Obtener una fruta específica por ID
    public function obtenerFruta($id) {
        $sql = "SELECT id, fruta, precio, temporada FROM precios1 WHERE id = $id";
        $resultado = $this->conexion->SetConsulta($sql);
        return $resultado ? $resultado[0] : null;
    }

    // Editar el precio de una fruta por ID
    public function editarFruta($id, $nuevoPrecio) {
        $sql = "UPDATE precios1 SET precio = $nuevoPrecio WHERE id = $id";
        return $this->conexion->SetConsulta($sql);
    }
}
?>
