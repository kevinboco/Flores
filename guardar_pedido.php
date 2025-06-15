<?php
include 'conexion.php';





$nombre = $_POST['nombre_cliente'];
$celular = $_POST['celular'];
$direccion = $_POST['direccion'];
$valor = $_POST['valor_ramo'];
$pagado = $_POST['cantidad_pagada'] ?? 0.00;
$fecha = $_POST['fecha_entrega'];
$descripcion = $_POST['descripcion'];
$nombre_ramo = $_POST['nombre_ramo'];
$sql = "INSERT INTO pedido (nombre_cliente, celular, direccion, valor_ramo, cantidad_pagada, fecha_entrega, descripcion, nombre_ramo) 
        VALUES ('$nombre', '$celular', '$direccion', '$valor', '$pagado', '$fecha', '$descripcion', '$nombre_ramo')";

if ($conn->query($sql) === TRUE) {
    echo "Pedido guardado correctamente. <a href='listar_pedidos.php'>Ver pedidos</a>";
    
}
?>
