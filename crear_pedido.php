<?php include 'conexion.php'; 
include 'texto circular.php';
include 'nav.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('uploads/fondo.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="container py-4">

<h2 class="mb-4">Nuevo Pedido</h2>
<form action="guardar_pedido.php" method="POST" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nombre del cliente</label>
        <input type="text" name="nombre_cliente" class="form-control" required>
    </div>
    <div class="mb-3">
    <label for="celular" class="form-label">Celular</label>
    <input type="text" class="form-control" name="celular" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Dirección</label>
        <input type="text" name="direccion" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Valor del ramo</label>
        <input type="number" step="1000" name="valor_ramo" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Cantidad pagada</label>
        <input type="number" step="1000" name="cantidad_pagada" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Fecha de entrega</label>
        <input type="date" name="fecha_entrega" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Descripcion del ramo</label>
        <input type="text" name="descripcion" class="form-control" required>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-success">Guardar pedido</button>
        <a href="listar_pedidos.php" class="btn btn-secondary">Ver pedidos</a>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
