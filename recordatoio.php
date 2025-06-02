<?php
// Obtener parámetros de la URL
$numero = isset($_GET['to']) ? $_GET['to'] : '';
$mensaje = isset($_GET['body']) ? $_GET['body'] : '';

// Validar que estén presentes
if (!$numero || !$mensaje) {
    echo "Faltan datos";
    exit;
}

$params = array(
    'token' => 'hsux4qfi6n0irjty',
    'to' => '+' . $numero,
    'body' => $mensaje
);

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.ultramsg.com/instance123499/messages/chat",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => http_build_query($params),
    CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo "Mensaje enviado correctamente: " . $response;
}
?>
