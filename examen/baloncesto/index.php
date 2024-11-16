<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Frutería</title>
</head>
<body>
    <h1>Formulario Baloncexto</h1>
    <form action="index.php" method="post">
        <p>
            <label for="TextNombre">Nombre:</label>
            <input type="text" name="Nombre" id="TextNombre" placeholder="Nombre">
        </p>
        <p>
            <label for="TextPosicion">Posición:</label>
            <input type="text" name="Posicion" id="TextPrecio" placeholder="Posición">
        </p>
        <p>
            <label for="TextPartidos">Partidos:</label>
            <input type="text" name="Partidos" id="TextPartidos" placeholder="Partidos">
        </p>
        <p>
            <label for="TextPuntos">Puntos:</label>
            <input type="text" name="Puntos" id="TextPuntos" placeholder="Puntos">
        </p>
        <p>
            <label for="TextRebotes">Rebotes:</label>
            <input type="text" name="Rebotes" id="TextRebotes" placeholder="Rebotes">
        </p>
        <p>
            <label for="TextAsistencias">Asistencias:</label>
            <input type="text" name="Asistencias" id="TextAsistencias" placeholder="Asistencias">
        </p>
        <input type="submit" name="Insertar" value="Insertar">
        <input type="submit" name="Eliminar" value="Eliminar">
        <input type="submit" name="Listar" value="Lista Jugadores">
        <input type="submit" name="Jugadoresdocepuntos" value="Jugadores que anotaron más de 12 puntos">
        <input type="submit" name="Jugadoresseisrebotes" value="Jugadores que han cogido más de 6 rebotes">
        <input type="submit" name="Jugadorescincoasistencias" value="Jugadores que han dado más de 5 asistencias">
        <input type="submit" name="Jugadorcompleto" value="Jugador Completo">
        <input type="submit" name="Bases" value="Bases más de 8 Asistencias">
        <input type="submit" name="Escoltasaleros" value="Escoltas o Aleros más de 15 puntos">
        <input type="submit" name="Alapivotpivot" value="Ala pivot o pivot más 7 rebotes">
    </form>

    <?php
     // Clase de conexión
     class Conexion {
        private $Servidor;
        private $Usuario;
        private $Pass;
        private $NombreBD;
        private $ComandoConexion;

        function __construct($servidor, $usuario, $pass, $nombreBD) {
            $this->Servidor = $servidor;
            $this->Usuario = $usuario;
            $this->Pass = $pass;
            $this->NombreBD = $nombreBD;
            $this->ComandoConexion = new mysqli($servidor, $usuario, $pass, $nombreBD);
            if ($this->ComandoConexion->connect_errno) {
                die("Error de conexión a la BD (" . $this->ComandoConexion->connect_errno . "): " . $this->ComandoConexion->connect_error);
            }
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

    // Crear la conexión
    $conexionBD = new Conexion("127.0.0.1", "mariadb", "mariadb", "mariadb");

    // Procesar el formulario según el botón presionado
    if (isset($_POST['Insertar'])) {
        // Validar solo si el botón "Insertar" fue presionado
        $nombre = filter_input(INPUT_POST, 'Nombre');
        $posicion = filter_input(INPUT_POST, 'Posicion');
        $partidos = filter_input(INPUT_POST, 'Partidos');
        $puntos = filter_input(INPUT_POST, 'Puntos');
        $rebotes = filter_input(INPUT_POST, 'Rebotes');
        $asistencias = filter_input(INPUT_POST, 'Asistencias');

        // Verificar que todos los campos sean válidos
        if ($nombre && $posicion && $partidos !== false && $puntos !== false && $rebotes !== false && $asistencias !== false) {
            // Consulta SQL corregida
            $sql = "INSERT INTO jugadores (nombre, posicion, partidos, puntos, rebotes, asistencias) 
                VALUES ('$nombre', '$posicion', $partidos, $puntos, $rebotes, $asistencias)";

            if ($conexionBD->SetConsulta($sql)) {
                echo "Jugador añadido correctamente.<br>";
            } else {
                echo "Error al insertar el jugador en la base de datos.<br>";
            }
        } else {
            echo "Por favor, complete todos los campos correctamente.<br>";
        }
    }
    

    if (isset($_POST['Listar'])) {
        // Consulta modificada para ordenar de mayor a menor por partidos, puntos, rebotes y asistencias
        $sql = "SELECT * FROM jugadores ORDER BY partidos DESC, puntos DESC, rebotes DESC, asistencias DESC";
        $Resultado = $conexionBD->SetConsulta($sql);
        if ($Resultado) {
            foreach ($Resultado as $fila) {
                echo "Nombre: " . htmlspecialchars($fila['nombre']) . "<br>";
                echo "Posicion: " . htmlspecialchars($fila['posicion']) . "<br>";
                echo "Partidos: " . htmlspecialchars($fila['partidos']) . "<br>";
                echo "Puntos: " . htmlspecialchars($fila['puntos']) . "<br>";
                echo "Rebotes: " . htmlspecialchars($fila['rebotes']) . "<br>";
                echo "Asistencias: " . htmlspecialchars($fila['asistencias']) . "<br><br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }
    

    if (isset($_POST['Jugadoresdocepuntos'])) {
        $sql = "SELECT nombre, puntos FROM jugadores WHERE puntos > 12";
        $Resultado = $conexionBD->SetConsulta($sql);
        if ($Resultado) {
            foreach ($Resultado as $fila) {
                echo "Nombre: " . htmlspecialchars($fila['nombre']) . "<br>";
                echo "Puntos: " . htmlspecialchars($fila['puntos']) . "<br><br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }

    if (isset($_POST['Jugadoresseisrebotes'])) {
        $sql = "SELECT nombre, rebotes FROM jugadores WHERE rebotes > 6";
        $Resultado = $conexionBD->SetConsulta($sql);
        if ($Resultado) {
            foreach ($Resultado as $fila) {
                echo "Nombre: " . htmlspecialchars($fila['nombre']) . "<br>";
                echo "Rebotes: " . htmlspecialchars($fila['rebotes']) . "<br><br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }

    if (isset($_POST['Jugadoresseisrebotes'])) {
        $sql = "SELECT nombre, rebotes FROM jugadores WHERE rebotes > 6";
        $Resultado = $conexionBD->SetConsulta($sql);
        if ($Resultado) {
            foreach ($Resultado as $fila) {
                echo "Nombre: " . htmlspecialchars($fila['nombre']) . "<br>";
                echo "Rebotes: " . htmlspecialchars($fila['rebotes']) . "<br><br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }

    if (isset($_POST['Jugadorescincoasistencias'])) {
        $sql = "SELECT nombre, asistencias FROM jugadores WHERE asistencias > 5";
        $Resultado = $conexionBD->SetConsulta($sql);
        if ($Resultado) {
            foreach ($Resultado as $fila) {
                echo "Nombre: " . htmlspecialchars($fila['nombre']) . "<br>";
                echo "Asistencias: " . htmlspecialchars($fila['asistencias']) . "<br><br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }
    if (isset($_POST['Jugadorcompleto'])) {
        $sql = "SELECT nombre, puntos, rebotes, asistencias FROM jugadores WHERE puntos > 10 AND rebotes >= 4 AND asistencias >= 4";
        $Resultado = $conexionBD->SetConsulta($sql);
        if ($Resultado) {
            foreach ($Resultado as $fila) {
                echo "Nombre: " . htmlspecialchars($fila['nombre']) . "<br>";
                echo "Puntos: " . htmlspecialchars($fila['puntos']) . "<br>";
                echo "Rebotes: " . htmlspecialchars($fila['rebotes']) . "<br>";
                echo "Asistencias: " . htmlspecialchars($fila['asistencias']) . "<br><br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }
    if (isset($_POST['Bases'])) {
        $sql = "SELECT nombre, asistencias FROM jugadores WHERE asistencias > 8 AND posicion='base'";
        $Resultado = $conexionBD->SetConsulta($sql);
        if ($Resultado) {
            foreach ($Resultado as $fila) {
                echo "Nombre: " . htmlspecialchars($fila['nombre']) . "<br>";
                echo "Asistencias: " . htmlspecialchars($fila['asistencias']) . "<br><br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }
    
    
    if (isset($_POST['Escoltasaleros'])) {
        $sql = "SELECT nombre, puntos, posicion FROM jugadores WHERE puntos > 15 AND (posicion='escolta' OR posicion='alero')";
        $Resultado = $conexionBD->SetConsulta($sql);
        if ($Resultado) {
            foreach ($Resultado as $fila) {
                echo "Nombre: " . htmlspecialchars($fila['nombre']) . "<br>";
                echo "Posición: " . htmlspecialchars($fila['posicion']) . "<br>";
                echo "Puntos: " . htmlspecialchars($fila['puntos']) . "<br><br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }
    if (isset($_POST['Alapivotpivot'])) {
        $sql = "SELECT nombre, rebotes, posicion FROM jugadores WHERE puntos > 15 AND (posicion='ala' OR posicion='ala pivot')";
        $Resultado = $conexionBD->SetConsulta($sql);
        if ($Resultado) {
            foreach ($Resultado as $fila) {
                echo "Nombre: " . htmlspecialchars($fila['nombre']) . "<br>";
                echo "Posicion: " . htmlspecialchars($fila['posicion']) . "<br>";
                echo "Rebotes: " . htmlspecialchars($fila['rebotes']) . "<br><br>";
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }
    if (isset($_POST['Eliminar'])) {// Validar solo si el botón "Crear" fue presionado
        $Nombre = filter_input(INPUT_POST, 'Nombre');
        
        if ($Nombre ) {
            $sql = "DELETE FROM jugadores WHERE nombre ='$Nombre'";
            if ($conexionBD->SetConsulta($sql)) {
                echo "Jugadores eliminados correctamente.<br>";
            } else {
                echo "Error al eliminar los jugadores en la base de datos.<br>";
            }
        } else {
            echo "Por favor, complete todos los campos para crear un jugador.<br>";
        }
    }
    $conexionBD->cerrarBD();
    ?>
</body>