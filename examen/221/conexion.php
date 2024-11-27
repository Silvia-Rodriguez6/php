<?php

class Conexion {
    private $ComandoConexion;

    public function __construct($host, $usuario, $password, $db) {
        try {
            $this->ComandoConexion = new mysqli($host, $usuario, $password, $db);
            if ($this->ComandoConexion->connect_error) {
                throw new Exception("Error de conexión: " . $this->ComandoConexion->connect_error);
            }
        } catch (Exception $e) {
            echo "Excepción capturada al conectar: " . $e->getMessage() . "<br>";
            exit;
        }
    }

    public function SetConsulta($consulta) {
        try {
            $Resultado = false;
            $PrimerComando = strtoupper(strtok(trim($consulta), " "));
            if (!$this->ComandoConexion) {
                throw new Exception("Conexión con BD perdida");
            }

            switch ($PrimerComando) {
                case 'SELECT':
                    $res = $this->ComandoConexion->query($consulta);
                    if ($res && $res->num_rows >= 1) {
                        $Resultado = $res->fetch_all(MYSQLI_ASSOC);
                    } elseif ($res) {
                        echo "La consulta no devolvió resultados.<br>";
                    } else {
                        throw new Exception("Error en la consulta SELECT: " . $this->ComandoConexion->error);
                    }
                    break;

                case 'INSERT':
                case 'UPDATE':
                case 'DELETE':
                    if ($this->ComandoConexion->query($consulta) === TRUE) {
                        $Resultado = true;
                        echo "Consulta ejecutada correctamente.<br>";
                    } else {
                        throw new Exception("Error en la consulta: " . $this->ComandoConexion->error);
                    }
                    break;

                default:
                    throw new Exception("Comando SQL no soportado: $PrimerComando");
            }

            return $Resultado;
        } catch (Exception $e) {
            echo "Excepción capturada: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    public function cerrarConexion() {
        try {
            if ($this->ComandoConexion) {
                $this->ComandoConexion->close();
            }
        } catch (Exception $e) {
            echo "Excepción capturada al cerrar la conexión: " . $e->getMessage() . "<br>";
        }
    }
}

?>
