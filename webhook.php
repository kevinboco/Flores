<?php
$data = file_get_contents("php://input");
$event = json_decode($data, true);

if (isset($event['data'])) {
    $from = $event['data']['from'];
    $message = strtolower(trim($event['data']['body']));

    require_once("conexion.php"); // incluimos la conexión

    if ($message === "pedidos mañana") {
        $mañana = date('Y-m-d', strtotime('+1 day'));

        $sql = "SELECT cliente, producto FROM pedidos WHERE fecha = '$mañana'";
        $resultado = $conexion->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            $respuesta = "Pedidos para mañana:\n";
            while ($fila = $resultado->fetch_assoc()) {
                $respuesta .= "- " . $fila['cliente'] . ": " . $fila['producto'] . "\n";
            }
        } else {
            $respuesta = "No hay pedidos para mañana.";
        }

        // Enviar respuesta por WhatsApp
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
