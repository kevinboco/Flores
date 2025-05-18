<?php
include 'conexion.php';

$id = $_GET['id'];
$nuevo_estado = $_GET['estado'];

$conn->query("UPDATE pedido SET estado = '$nuevo_estado' WHERE id = $id");

header("Location: listar_pedidos.php");
