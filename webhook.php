<?php
// webhook.php

// Incluye tu conexión a la base de datos
include 'conexion.php';

$data = file_get_contents("php://input");
$event = json_decode($data, true);

if (isset($event['data'])) {
    // Guardar log para debugging
    file_put_contents('log.txt', json_encode($event) . "\n", FILE_APPEND | LOCK_EX);

    $from = $event['data']['from'];  // Número del remitente, e.g. "573016838051@c.us"
    $message = trim($event['data']['body']);  // Texto que envió el usuario

    // Solo procesar mensajes tipo chat
    if ($event['data']['type'] == 'chat') {

        // Ejemplo: Si el usuario escribe "¿Qué pedidos tengo para mañana?"
        // puedes hacer un parser básico para obtener la fecha
        $date = null;
        if (preg_match('/mañana/i', $message)) {
            $date = date('Y-m-d', strtotime('+1 day'));
        } else if (preg_match('/hoy/i', $message)) {
            $date = date('Y-m-d');
        } else if (preg_match('/para (\d{4}-\d{2}-\d{2})/', $message, $matches)) {
            $date = $matches[1];
        }

        // Si detectó fecha, consulta pedidos
        if ($date) {
            $numero = preg_replace('/\D/', '', $from); // quita todo menos números

            // Consulta los pedidos para ese número y fecha
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
            $reply = "No entendí la fecha. Por favor escribe '¿Qué pedidos tengo para hoy?', 'mañana' o 'para YYYY-MM-DD'.";
        }

        // Enviar respuesta vía API UltraMSG
        $params = array(
            'token' => 'hsux4qfi6n0irjty',
            'to' => '+' . $numero,
            'body' => $reply
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ultramsg.com/instance123499/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => array("content-type: application/x-www-form-urlencoded")
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }
}
?>


