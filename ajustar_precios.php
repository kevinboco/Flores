<?php
include 'conexion.php';
include 'nav.php';

$mensaje = "";

// Ruta donde se guarda la acción anterior
$historial_file = 'historial_ajuste.txt';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cantidad = floatval($_POST['cantidad']);
    $accion = $_POST['accion'];

    if ($accion === 'subir') {
        $sql = "UPDATE pedido SET valor_ramo = valor_ramo + ?";
        $mensaje = "Subiste a todos los precios $" . number_format($cantidad, 0);
    } elseif ($accion === 'bajar') {
        $sql = "UPDATE pedido SET valor_ramo = GREATEST(0, valor_ramo - ?)";
        $mensaje = "Restaste a todos los precios $" . number_format($cantidad, 0);
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d", $cantidad);
    $stmt->execute();

    file_put_contents($historial_file, $mensaje);
}
$ultima_accion = file_exists($historial_file) ? file_get_contents($historial_file) : "No has realizado ajustes todavía.";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ajustar precios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2 class="mb-4">Ajustar precios de todos los pedidos</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= $mensaje ?></div>
    <?php endif; ?>

    <div class="alert alert-info">
        Última acción: <strong><?= $ultima_accion ?></strong>
    </div>

    <form method="post" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="cantidad" class="form-label">¿Cuánto deseas ajustar?</label>
            <input type="number" name="cantidad" id="cantidad" step="1000" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Acción</label>
            <select name="accion" class="form-select" required>
                <option value="subir">Subir precios</option>
                <option value="bajar">Bajar precios</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Aplicar ajuste</button>
    </form>
</body>
</html>
