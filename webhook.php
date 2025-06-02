<?php
// Guardar lo que llega al webhook (útil para depuración)
file_put_contents("log.txt", file_get_contents("php://input") . PHP_EOL, FILE_APPEND);

// Leer el JSON que envía Ultramsg
$data = json_decode(file_get_contents("php://input"), true);

// Verifica si hay mensaje y número
if (isset($data["body"]) && isset($data["from"])) {
    $mensaje = strtolower(trim($data["body"]));
    $numero = $data["from"]; // Ej: 57301xxxxxxx

    // Conexión a base de datos
    include("conexion.php");

    // Si el mensaje contiene la palabra "mañana"
    if (strpos($mensaje, "mañana") !== false) {
        $fecha = date("Y-m-d", strtotime("+1 day"));
    }
    // Si el mensaje contiene "hoy"
    elseif (strpos($mensaje, "hoy") !== false) {
        $fecha = date("Y-m-d");
    }
    // Si menciona una fecha exacta como "2025-06-03"
    elseif (preg_match("/\d{4}-\d{2}-\d{2}/", $mensaje, $coincidencias)) {
        $fecha = $coincidencias[0];
    }

    if (isset($fecha)) {
        $sql = "SELECT * FROM pedido WHERE fecha_entrega = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $fecha);
        $stmt->execute();
        $result = $stmt->get_result();

        $respuesta = "📦 Pedidos para $fecha:\n";
        $hayPedidos = false;

        while ($row = $result->fetch_assoc()) {
            $hayPedidos = true;
            $respuesta .= "\n🧍 Cliente: {$row['nombre_cliente']}\n📍 Dirección: {$row['direccion']}\n💐 Valor: {$row['valor_ramo']}\n";
        }

        if (!$hayPedidos) {
            $respuesta = "No hay pedidos programados para $fecha.";
        }

        // Enviar respuesta por Ultramsg
        $params = array(
            'token' => 'hsux4qfi6n0irjty',
            'to' => $numero,
            'body' => $respuesta
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ultramsg.com/instance123499/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => array("Content-Type: application/x-www-form-urlencoded")
        ));

        $response = curl_exec($curl);
        curl_close($curl);
    }
}
?>

