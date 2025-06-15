<?php
include 'conexion.php';

$sql = "SELECT * FROM pedido ORDER BY id DESC LIMIT 1"; // solo el más reciente
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $pedido = $result->fetch_assoc();
    echo "Último pedido: " . $pedido['nombre_cliente'] . ", " . $pedido['celular'] . ", " . $pedido['direccion'] . ", entrega: " . $pedido['fecha_entrega'];
} else {
    echo "No hay pedidos.";
}
?>
