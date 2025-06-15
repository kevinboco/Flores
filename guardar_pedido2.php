<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_cliente'] ?? '';
    $celular = $_POST['celular'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    

    if ($nombre !== '' && $celular !== '' && $direccion !== '') {
        $stmt = $conn->prepare("INSERT INTO pedido (nombre_cliente, celular, direccion) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $celular, $direccion);

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
