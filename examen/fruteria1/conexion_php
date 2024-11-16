<?php
// conexion.php

class Conexion {
    private static $instancia = null; // Singleton
    private $ComandoConexion;

    private function __construct($servidor, $usuario, $pass, $nombreBD) {
        $this->ComandoConexion = new mysqli($servidor, $usuario, $pass, $nombreBD);
        if ($this->ComandoConexion->connect_errno) {
            die("Error de conexión a la BD (" . $this->ComandoConexion->connect_errno . "): " . $this->ComandoConexion->connect_error);
        }
    }

    public static function obtenerConexion() {
        if (self::$instancia === null) {
            self::$instancia = new Conexion("127.0.0.1", "mariadb", "mariadb", "mariadb");
        }
        return self::$instancia;
    }

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
                    echo "Consulta ejecutada correctamente.<br>";
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

    public function cerrarBD() {
        if ($this->ComandoConexion) {
            $this->ComandoConexion->close();
        }
    }
}
?>
