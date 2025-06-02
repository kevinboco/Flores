<?php
$input = json_decode(file_get_contents('php://input'), true);
$numero = $input['from'] ?? '';
$mensaje = strtolower(trim($input['body'] ?? ''));

if ($numero && $mensaje) {
    require_once("conexion.php");

    $respuesta = "Lo siento, no entendí tu pregunta.";

    // -----------------------------
    // 🧠 DETECTAR FECHA EN EL MENSAJE
    // -----------------------------
    $fecha_detectada = null;

    // Buscar fechas con formato: dd/mm/yyyy, dd-mm-yyyy, dd/mm, dd-mm
    if (preg_match('/(\d{1,2})[\/\-](\d{1,2})([\/\-](\d{4}))?/', $mensaje, $partes)) {
        $dia = str_pad($partes[1], 2, "0", STR_PAD_LEFT);
        $mes = str_pad($partes[2], 2, "0", STR_PAD_LEFT);
        $anio = $partes[3] ? str_replace(['/', '-'], '', $partes[3]) : date('Y'); // Si no dice el año, usa el actual

        $fecha_detectada = "$anio-$mes-$dia";
    }

    // Si se detectó una fecha, buscar los pedidos de ese día
    if ($fecha_detectada) {
        $sql = "SELECT nombre_cliente, direccion, valor_ramo FROM pedido WHERE fecha_entrega = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $fecha_detectada);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $respuesta = "Pedidos para el " . date("d/m/Y", strtotime($fecha_detectada)) . ":\n";
            while ($row = $resultado->fetch_assoc()) {
                $respuesta .= "- " . $row['nombre_cliente'] . " (" . $row['direccion'] . "), $" . number_format($row['valor_ramo']) . "\n";
            }
        } else {
            $respuesta = "No tienes pedidos para el " . date("d/m/Y", strtotime($fecha_detectada)) . ".";
        }

        $stmt->close();
    }

    $conexion->close();

    // Enviar respuesta
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
        CURLOPT_HTTPHEADER => array("content-type: application/x-www-form-urlencoded")
    ));

    curl_exec($curl);
    curl_close($curl);
}
