<?php
include 'conexion.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM pedido WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_cliente'];
    $celular = $_POST['celular'];
    $direccion = $_POST['direccion'];
    $fecha_entrega = $_POST['fecha_entrega'];
    $valor = $_POST['valor_ramo'];
    $pagado = $_POST['cantidad_pagada'];
    $estado = $_POST['estado'];

    $stmt = $conn->prepare("UPDATE pedido SET nombre_cliente=?, celular=?, direccion=?, fecha_entrega=?, valor_ramo=?, cantidad_pagada=?, estado=? WHERE id=?");
    $stmt->bind_param("ssssdisi", $nombre, $celular, $direccion, $fecha_entrega, $valor, $pagado, $estado, $id);
    $stmt->execute();

    header("Location: listar_pedidos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

<h2 class="mb-4">Editar Pedido</h2>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Nombre del Cliente</label>
        <input type="text" name="nombre_cliente" class="form-control" value="<?= htmlspecialchars($pedido['nombre_cliente']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Celular</label>
        <input type="text" name="celular" class="form-control" value="<?= htmlspecialchars($pedido['celular']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Dirección</label>
        <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($pedido['direccion']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Fecha de Entrega</label>
        <input type="date" name="fecha_entrega" class="form-control" value="<?= $pedido['fecha_entrega'] ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Valor del Ramo</label>
        <input type="number" name="valor_ramo" class="form-control" value="<?= $pedido['valor_ramo'] ?>" step="0.01" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Cantidad Pagada</label>
        <input type="number" name="cantidad_pagada" class="form-control" value="<?= $pedido['cantidad_pagada'] ?>" step="0.01" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
            <option <?= $pedido['estado'] == 'En proceso' ? 'selected' : '' ?>>En proceso</option>
            <option <?= $pedido['estado'] == 'Listo' ? 'selected' : '' ?>>Listo</option>
            <option <?= $pedido['estado'] == 'Enviado' ? 'selected' : '' ?>>Enviado</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <a href="listar_pedidos.php" class="btn btn-secondary">Cancelar</a>
</form>

</body>
</html>
