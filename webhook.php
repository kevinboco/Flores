<?php
$data = file_get_contents("php://input");
$event = json_decode($data, true);

if (isset($event['data'])) {
    $from = $event['data']['from'];   // número del remitente con @c.us
    $message = trim($event['data']['body']);

    if ($message === "1") {
        // Prepara los datos para enviar la respuesta
        $params = [
            'token' => 'hsux4qfi6n0irjty',
            'to' => $from,
            'body' => "1"
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
