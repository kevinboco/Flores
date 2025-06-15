<?php
include 'texto circular.php';
include 'nav.php';
include 'conexion.php';

$titulo = $_GET['titulo'] ?? '';

if (!$titulo) {
    echo "<div class='alert alert-danger'>No se especificó ningún ramo.</div>";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM catalogo_ramos WHERE titulo = ?");
$stmt->bind_param("s", $titulo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<div class='alert alert-warning'>No se encontró ningún ramo con ese nombre.</div>";
    exit;
}

$ramo = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Ramo: <?= htmlspecialchars($ramo['titulo']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h2 class="mb-4">Detalles del Ramo</h2>

    <div class="card mb-4 mx-auto" style="max-width: 700px;">
        <img src="uploads/<?= htmlspecialchars($ramo['imagen']) ?>" class="card-img-top" alt="Imagen del ramo">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($ramo['titulo']) ?></h5>
            <p class="card-text"><strong>Valor:</strong> $<?= number_format($ramo['valor'], 2) ?></p>
            <p class="card-text"><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($ramo['description'])) ?></p>
            <p class="card-text"><strong>Categoría:</strong> <?= htmlspecialchars($ramo['categoria']) ?></p>
            <a href="listar_pedidos.php" class="btn btn-secondary">← Volver a pedidos</a>
        </div>
    </div>

</body>
</html>
