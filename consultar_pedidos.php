<?php
include 'conexion.php';

$sql = "SELECT * FROM pedido ORDER BY id DESC";
$result = $conn->query($sql);

$pedidos = [];

if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        $pedidos[] = $fila;
    }
}

// Establecer cabecera para que Siri lo entienda como JSON
header('Content-Type: application/json');
echo json_encode($pedidos);
?>
