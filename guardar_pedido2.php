<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_cliente'] ?? '';
    $celular = $_POST['celular'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $fecha_entrega = $_POST['fecha_entrega'] ?? '';

    if ($nombre !== '' && $celular !== '' && $direccion !== '' && $fecha_entrega !== '') {
        $stmt = $conn->prepare("INSERT INTO pedido (nombre_cliente, celular, direccion, fecha_entrega) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $celular, $direccion, $fecha_entrega);

        if ($stmt->execute()) {
            echo "Pedido guardado correctamente";
        } else {
            echo "Error al guardar el pedido";
        }
    } else {
        echo "Faltan datos";
    }
} else {
    echo "Método no permitido";
}
?>
