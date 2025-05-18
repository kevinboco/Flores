<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "pedidos"; // Cambia esto por el nombre real de tu BD

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
