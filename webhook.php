<?php
include 'conexion.php';  // tu conexión a la BD

$data = file_get_contents("php://input");
$event = json_decode($data, true);

if (isset($event['data'])) {
    $from = $event['data']['from'];  // e.g. 573016838051@c.us
    $message = trim($event['data']['body']);
    $type = $event['data']['type'];

    if ($type == 'chat') {
        // Detecta la fecha
        if (preg_match('/mañana/i', $message)) {
            $date = date('Y-m-d', strtotime('+1 day'));
        } else {
            // Si no reconoce la fecha, responder con mensaje de ayuda
            $date = null;
        }

        if ($date) {
            $numero = preg_replace('/\D/', '', $from);

            $sql = "SELECT nombre_cliente, direccion, valor_ramo, estado FROM pedido WHERE celular LIKE '%$numero%' AND fecha_entrega = '$date'";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $reply = "Pedidos para el $date:\n";
                while ($row = $result->fetch_assoc()) {
                    $reply .= "- {$row['nombre_cliente']} ({$row['direccion']}), Valor: {$row['valor_ramo']}, Estado: {$row['estado']}\n";
                }
            } else {
                $reply = "No tienes pedidos para el $date.";
            }
        } else {
            $reply = "No entendí la fecha. Por favor escribe '¿Qué pedidos tengo para mañana?'";
        }

        // Enviar respuesta por UltraMSG
        $params = [
            'token' => 'hsux4qfi6n0irjty',
            'to' => '+' . $numero,
            'body' => $reply
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

        // Opcional: guarda respuesta para debug
        file_put_contents('response_log.txt', date('Y-m-d H:i:s') . " - $reply\n", FILE_APPEND);
    }
}
?>
