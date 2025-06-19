<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_cliente'] ?? '';

    if ($nombre !== '') {
        $stmt = $conn->prepare("INSERT INTO pedido (nombre_cliente) VALUES (?)");
        $stmt->bind_param("s", $nombre);

        if ($stmt->execute()) {
            echo "Pedido guardado correctamente";
        } else {
            echo "Error al guardar el pedido";
        }
    } else {
        echo "Falta el nombre del cliente";
    }
} else {
    echo "Método no permitido";
}
?>
