<?php
require_once 'conexion.php'; 

class BorrarFruta {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::obtenerConexion();
    }

    public function obtenerFrutas() {
        // Consultar todas las frutas
        $sql = "SELECT id, fruta, precio, temporada FROM precios";
        return $this->conexion->SetConsulta($sql);
    }

    public function procesarEliminacion() {
        // Validar el ID enviado
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $sql = "DELETE FROM precios WHERE id = $id";
            if ($this->conexion->SetConsulta($sql)) {
                echo "<p>Fruta eliminada correctamente.</p>";
            } else {
                echo "<p>Error al eliminar la fruta.</p>";
            }
        } else {
            echo "<p>ID inválido.</p>";
        }

        echo "<a href='borrar_fruta.php'>Volver al listado</a>";
    }

    public function mostrarFrutas() {
        $frutas = $this->obtenerFrutas();

        if ($frutas) {
            echo "<table border='1'>";
            echo "<tr><th>Nombre</th><th>Precio (€)</th><th>Temporada</th><th>Acciones</th></tr>";
            foreach ($frutas as $fruta) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($fruta['fruta']) . "</td>";
                echo "<td>" . htmlspecialchars($fruta['precio']) . "</td>";
                echo "<td>" . htmlspecialchars($fruta['temporada']) . "</td>";
                echo "<td>
                        <form method='post'>
                            <input type='hidden' name='id' value='" . $fruta['id'] . "'>
                            <input type='submit' name='borrar' value='Borrar'>
                        </form>
                      </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay frutas en el catálogo.</p>";
        }
    }
}

$borrarFruta = new BorrarFruta();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrar'])) {
    $borrarFruta->procesarEliminacion();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Borrar Frutas</title>
</head>
<body>
    <h1>Borrar Frutas</h1>
    <?php
    // Mostrar el listado de frutas
    $borrarFruta->mostrarFrutas();
    ?>
</body>
</html>
