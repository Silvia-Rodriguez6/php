<!DOCTYPE html>
<html>
<head>
    <title>Transferencias</title>
    <meta charset="UTF-8">
</head>
<body>
    <div>
        <form action="pastagansa.php" method="POST">
            <p><label>Sujeto:</label><input type="text" name="sujeto"></p>
            <p><label>Código de Transferencia:</label><input type="text" name="codtransfer"></p>
            <p><label>Cantidad:</label><input type="text" name="cantidad"></p>
            <p><label>Fecha y Hora (YYYY-MM-DDThh:mm):</label><input type="datetime-local" name="fecha_hora"></p>
            <div class="form-group">
                <button type="submit" name="nueva" class="btn btn-primary">Nueva</button>
                <button type="submit" name="reclamar" class="btn btn-secondary">Reclamar</button>
                <button type="submit" name="anular_reclamar" class="btn btn-secondary">Anular Reclamo</button>
                <button type="submit" name="recibidas" class="btn btn-secondary">Recibidas</button>
                <button type="reset" name="cancelar" class="btn btn-danger">Cancelar</button>
            </div>
        </form>
    </div>
</body>
</html>
