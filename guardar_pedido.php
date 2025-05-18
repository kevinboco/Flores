<?php
include 'conexion.php';

function enviarNotificacionTelegram($mensaje) {
    $botToken = '7590591675:AAHiatr9TqbXkd4_7F7lQBure5n6U-0C14Y';
    $chatId = '6674396003'; // ID del usuario que recibe la notificación
    $url = "https://api.telegram.org/bot$botToken/sendMessage";

    $data = [
        'chat_id' => $chatId,
        'text' => $mensaje,
        'parse_mode' => 'HTML'
    ];

    $opciones = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type:application/x-www-form-urlencoded",
            'content' => http_build_query($data),
        ],
    ];
    $contexto = stream_context_create($opciones);
    file_get_contents($url, false, $contexto);
}




$nombre = $_POST['nombre_cliente'];
$celular = $_POST['celular'];
$direccion = $_POST['direccion'];
$valor = $_POST['valor_ramo'];
$pagado = $_POST['cantidad_pagada'] ?? 0.00;
$fecha = $_POST['fecha_entrega'];

$sql = "INSERT INTO pedido (nombre_cliente, celular, direccion, valor_ramo, cantidad_pagada, fecha_entrega) 
        VALUES ('$nombre', '$celular', '$direccion', '$valor', '$pagado', '$fecha')";

if ($conn->query($sql) === TRUE) {
    echo "Pedido guardado correctamente. <a href='listar_pedidos.php'>Ver pedidos</a>";
     $mensaje = "📄 Se ha subido un nuevo pedido:\n\n🗓 Fecha: $fecha\n✈️ Viaje: \n\nPor favor, revisa la información.";
        enviarNotificacionTelegram($mensaje);} else {
    echo "Error: " . $conn->error;
}
?>
