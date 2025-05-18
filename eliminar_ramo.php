<?php
include 'conexion.php';

$id = $_GET['id'] ?? null;
if ($id) {
    // Opcional: borrar imagen física aquí si quieres
    // Primero obtener nombre imagen:
    $stmt = $conn->prepare("SELECT imagen FROM catalogo_ramos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ramo = $result->fetch_assoc();
    
    if ($ramo) {
        $rutaImagen = 'uploads/' . $ramo['imagen'];
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }

    // Borrar registro
    $stmt = $conn->prepare("DELETE FROM catalogo_ramos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: listar_catalogo.php');
exit;
