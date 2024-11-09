<!DOCTYPE html>
<html>
<head>
    <title>Notas</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div>
        <form action="notas.php" method="POST">
            <p><label>Nombre:</label> <input type="text" name="nombre"></p>
            <p><label>DNI:</label> <input type="text" name="dni"></p>
            <p><label>Grupo:</label> <input type="text" name="grupo"></p>
            <p><label>Fecha y Hora:</label> <input type="datetime-local" name="fecha_hora"></p>
            <p><label>Asignatura:</label> <input type="text" name="asignatura"></p>
            <p><label>Nota:</label> <input type="text" name="nota"></p>
            <div>
                <button type="submit" name="nueva">Nueva</button>
                <button type="submit" name="media">Nota Media</button>
                <button type="submit" name="mostrar">Mostrar Usuarios</button>
                <button type="reset" name="cancelar">Limpiar Formulario</button>
            </div>
        </form>
    </div>
</body>
</html>