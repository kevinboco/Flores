<?php
include 'conexion.php'; // Asegúrate de que este archivo contenga tu conexión a MySQL

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_cliente = $_POST['nombre_cliente'] ?? '';

    $sql = "INSERT INTO pedido (nombre_cliente) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombre_cliente);

    if ($stmt->execute()) {
        echo "Guardado: $nombre_cliente";
    } else {
        echo "Error al guardar.";
    }
} else {
    echo "Método no permitido.";
}
?>
