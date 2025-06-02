<?php
require 'conexion.php'; // Asegúrate de que este archivo define $conexion correctamente

$data = file_get_contents("php://input");
$event = json_decode($data, true);

if (isset($event['data'])) {
    $from = $event['data']['from'];
    $message = trim($event['data']['body']);

    if ($message === "1") {
        // Calcular fecha de mañana
        date_default_timezone_set('America/Bogota');
        $mañana = date('Y-m-d', strtotime('+1 day'));

        // Consulta
        $sql = "SELECT nombre_cliente, direccion, descripcion, valor_ramo, cantidad_pagada 
                FROM pedido 
                WHERE fecha_entrega = '$mañana'";
        $resultado = $conexion->query($sql);

        if (!$resultado) {
            $respuesta = "⚠️ Error en la base de datos: " . $conexion->error;
            file_put_contents("log.txt", "Error SQL: " . $conexion->error . "\n", FILE_APPEND);
        } elseif ($resultado->num_rows > 0) {
            $respuesta = "📦 *Pedidos para mañana ($mañana)*:\n";
            while ($fila = $resultado->fetch_assoc()) {
                $respuesta .= "\n👤 " . $fila['nombre_cliente'] .
                              "\n📍 " . $fila['direccion'] .
                              "\n🌸 " . $fila['descripcion'] .
                              "\n💰 Valor: $" . number_format($fila['valor_ramo'], 0, ',', '.') .
                              "\n🧾 Pagado: $" . number_format($fila['cantidad_pagada'], 0, ',', '.') . "\n";
            }
        } else {
            $respuesta = "📭 No hay pedidos registrados para mañana ($mañana).";
            file_put_contents("log.txt", "Consulta OK, pero sin resultados\n", FILE_APPEND);
        }

        // Enviar respuesta vía Ultramsg
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
        $response = curl_exec($curl);
        curl_close($curl);
    }
}
?>
