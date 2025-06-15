<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_cliente'] ?? '';
    $celular = $_POST['celular'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $fecha_entrega = $_POST['fecha_entrega'] ?? '';

    if ($nombre !== '' && $celular !== '' && $direccion !== '' && $fecha_entrega !== '') {
        $sql = "INSERT INTO pedido (
            nombre_cliente,
            celular,
            direccion,
            valor_ramo,
            cantidad_pagada,
            estado,
            fecha_entrega,
            descripcion,
            nombre_ramo
        ) VALUES (?, ?, ?, DEFAULT, DEFAULT, DEFAULT, ?, DEFAULT, DEFAULT)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nombre, $celular, $direccion, $fecha_entrega);

        if ($stmt->execute()) {
            echo "Pedido guardado correctamente";
        } else {
            echo "Error al guardar el pedido: " . $stmt->error;
        }
    } else {
        echo "Faltan datos";
    }
} else {
    echo "Método no permitido";
}
?>
