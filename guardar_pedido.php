<?php
$data = file_get_contents("php://input");
$event = json_decode($data, true);

if (isset($event['data'])) {
    $from = $event['data']['from'];
    $message = strtolower(trim($event['data']['body']));

    require_once("conexion.php"); // Conexión a la base de datos

    if ($message === "pedidos mañana") {
        $mañana = date('Y-m-d', strtotime('+1 day'));

        $sql = "SELECT nombre_cliente, direccion, descripcion, valor_ramo, cantidad_pagada 
                FROM pedido 
                WHERE fecha_entrega = '$mañana'";
        $resultado = $conexion->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            $respuesta = "📦 *Pedidos para mañana ($mañana)*:\n";
            while ($fila = $resultado->fetch_assoc()) {
                $respuesta .= "\n👤 " . $fila['nombre_cliente'] .
                              "\n📍 " . $fila['direccion'] .
                              "\n🌸 " . $fila['descripcion'] .
                              "\n💰 Valor: $" . number_format($fila['valor_ramo'], 0, ',', '.') .
                              "\n🧾 Pagado: $" . number_format($fila['cantidad_pagada'], 0, ',', '.') . "\n";
            }
        } else {
            $respuesta = "📭 No hay pedidos registrados para mañana.";
        }

        // Enviar respuesta
        $params = [
            'token' => 'hsux4qfi6n0irjty',
            'to' => $from,
            'body' => $respuesta
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.ultramsg.com/instance123499/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => ["content-type: application/x-www-form-urlencoded"]
        ]);
        curl_exec($curl);
        curl_close($curl);
    }

    $conexion->close();
}
?>
