<?php
include 'conexion.php';

$id = $_GET['id'];

$conn->query("DELETE FROM pedido WHERE id = $id");

header("Location: listar_pedidos.php");
