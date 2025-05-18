<?php
include 'conexion.php';

$result = $conn->query("SELECT * FROM catalogo_ramos ORDER BY titulo ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Ramos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

<h2 class="mb-4">Catálogo de Ramos</h2>

<a href="crear_ramo.php" class="btn btn-primary mb-4">Agregar Nuevo Ramo</a>
<a href="listar_pedidos.php" class="btn btn-primary mb-4">Listar Pedidos</a>
<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="uploads/<?= htmlspecialchars($row['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['titulo']) ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($row['titulo']) ?></h5>
                    <p class="card-text fw-bold text-success">$<?= number_format($row['valor'], 2) ?></p>
                    <div class="mt-auto">
                        <a href="editar_ramo.php?id=<?= $row['id'] ?>" class="btn btn-outline-secondary btn-sm me-2">Editar</a>
                        <a href="eliminar_ramo.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar este ramo?')">Eliminar</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No hay ramos en el catálogo.</p>
    <?php endif; ?>
</div>

</body>
</html>
