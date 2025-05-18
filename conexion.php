<?php
$host = "mysql.hostinger.com";
$user = "u648222299_keboco2";  
$password = "Bucaramanga3011";
$database = "u648222299_pedidos"; // Cambia esto por el nombre real de tu BD

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
