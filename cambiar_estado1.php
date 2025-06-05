<?php
include 'conexion.php';

// Obtener parámetros
$id = $_GET['id'] ?? null;
$estado = $_GET['estado'] ?? null;
$fecha = $_GET['fecha'] ?? null;

if ($id && $estado) {
    $stmt = $conn->prepare("UPDATE pedido SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $estado, $id);
    $stmt->execute();
}

if ($fecha) {
    header("Location: ver_pedidos_dia.php?fecha=" . urlencode($fecha));
} else {
    header("Location: listar_pedidos.php");
}
exit;
