<?php

class Conexion {

    private $Servidor;
    private $Usuario;
    private $Pass;
    private $NombreBD;
    private $ComandoConexion;

    // Constructor
    function __construct($servidor, $usuario, $pass, $nombreBD) {
        $this->Servidor = $servidor;
        $this->Usuario = $usuario;
        $this->Pass = $pass;
        $this->NombreBD = $nombreBD;

        // Conectar a la base de datos
        $this->ComandoConexion = new mysqli($servidor, $usuario, $pass, $nombreBD);

        if ($this->ComandoConexion->connect_errno) {
            die("Error de conexión a la BD (" . $this->ComandoConexion->connect_errno . "): " . $this->ComandoConexion->connect_error);
        }

        echo "OK conexión a la BD<br>";
    }

    // Método para ejecutar consultas
    public function SetConsulta($consulta) {
        $Resultado = false;
        $PrimerComando = strtoupper(strtok(trim($consulta), " "));

        if (!$this->ComandoConexion) {
            echo "Conexión con BD perdida";
            return false;
        }

        switch ($PrimerComando) {
            case 'SELECT':
                $res = $this->ComandoConexion->query($consulta);
                if ($res && $res->num_rows >= 1) {
                    $Resultado = $res->fetch_all(MYSQLI_ASSOC);
                } elseif ($res) {
                    echo "La consulta no devolvió resultados.<br>";
                } else {
                    echo "Error en la consulta SELECT: " . $this->ComandoConexion->error . "<br>";
                }
                break;

            case 'INSERT':
            case 'UPDATE':
            case 'DELETE':
                if ($this->ComandoConexion->query($consulta) === TRUE) {
                    $Resultado = true;
                    echo "Consulta ejecutada correctamente: $PrimerComando<br>";
                } else {
                    echo "Error en la consulta: " . $this->ComandoConexion->error . "<br>";
                }
                break;

            default:
                echo "Comando SQL no soportado: $PrimerComando<br>";
                break;
        }

        return $Resultado;
    }

    // Método para cerrar la conexión
    public function cerrarBD() {
        if ($this->ComandoConexion) {
            $this->ComandoConexion->close();
            echo "CERRADA conexión a la BD<br>";
        }
    }
}

// Crear la conexión
$conexionBD = new Conexion("127.0.0.1", "mariadb", "mariadb", "mariadb");

// Obtener datos del formulario
$NombreFruta = filter_input(INPUT_POST, 'Nombre');
$Precio = filter_input(INPUT_POST, 'Precio', FILTER_VALIDATE_FLOAT);
$Temporada = filter_input(INPUT_POST, 'Temporada');

if (isset($_POST['Crear'])) {
    $sql = "INSERT INTO precios (fruta, precio, temporada) VALUES ('$NombreFruta', '$Precio', '$Temporada')";
    if ($conexionBD->SetConsulta($sql)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al insertar la fruta en la base de datos.<br>";
    }
}

if (isset($_POST['FrutasOrdenadas'])) {
    $sql = "SELECT fruta, precio FROM precios ORDER BY precio ASC";
    $Resultado = $conexionBD->SetConsulta($sql);
    if ($Resultado) {
        foreach ($Resultado as $fila) {
            echo "Fruta: " . htmlspecialchars($fila['fruta']) . "<br>";
            echo "Precio: " . htmlspecialchars($fila['precio']) . "<br><br>";
        }
    } else {
        echo "No se encontraron resultados.";
    }
}

if (isset($_POST['filtroA'])) {
    $sql = "SELECT fruta, precio FROM precios WHERE precio > 1.5";
    $Resultado = $conexionBD->SetConsulta($sql);
    if ($Resultado) {
        foreach ($Resultado as $fila) {
            echo "Fruta: " . htmlspecialchars($fila['fruta']) . "<br>";
            echo "Precio: " . htmlspecialchars($fila['precio']) . "<br><br>";
        }
    } else {
        echo "No se encontraron resultados.";
    }
}

if (isset($_POST['filtroB'])) {
    $sql = "SELECT fruta, precio, temporada FROM precios WHERE temporada = 'otoño'";
    $Resultado = $conexionBD->SetConsulta($sql);
    if ($Resultado) {
        foreach ($Resultado as $fila) {
            echo "Fruta: " . htmlspecialchars($fila['fruta']) . "<br>";
            echo "Precio: " . htmlspecialchars($fila['precio']) . "<br><br>";
        }
    } else {
        echo "No se encontraron resultados.";
    }
}

if (isset($_POST['filtroC'])) {
    $sql = "SELECT fruta, precio, temporada FROM precios WHERE temporada = 'otoño' OR temporada = 'anual'";
    $Resultado = $conexionBD->SetConsulta($sql);
    if ($Resultado) {
        foreach ($Resultado as $fila) {
            echo "Fruta: " . htmlspecialchars($fila['fruta']) . "<br>";
            echo "Precio: " . htmlspecialchars($fila['precio']) . "<br><br>";
        }
    } else {
        echo "No se encontraron resultados.";
    }
}

if (isset($_POST['filtroD'])) {
    $sql = "SELECT fruta, precio, temporada FROM precios WHERE precio < 0.5 AND temporada = 'anual'";
    $Resultado = $conexionBD->SetConsulta($sql);
    if ($Resultado) {
        foreach ($Resultado as $fila) {
            echo "Fruta: " . htmlspecialchars($fila['fruta']) . "<br>";
            echo "Precio: " . htmlspecialchars($fila['precio']) . "<br><br>";
        }
    } else {
        echo "No se encontraron resultados.";
    }
}

$conexionBD->cerrarBD();
?>
