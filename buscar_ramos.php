<?php
include 'conexion.php';
$term = $_GET['term'] ?? '';
$sugerencias = [];

if ($term !== '') {
    $stmt = $conn->prepare("SELECT titulo FROM catalogo_ramos WHERE titulo LIKE CONCAT('%', ?, '%') LIMIT 5");
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $sugerencias[] = $row['titulo'];
    }
}
header('Content-Type: application/json');
echo json_encode($sugerencias);