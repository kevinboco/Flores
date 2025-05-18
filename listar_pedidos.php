<?php
include 'conexion.php';

// Obtener filtro si existe
$filtro = isset($_GET['estado']) ? $_GET['estado'] : '';
$sql = "SELECT * FROM pedido";
if ($filtro != '') {
    $stmt = $conn->prepare("SELECT * FROM pedido WHERE estado = ? ORDER BY fecha_entrega ASC");
    $stmt->bind_param("s", $filtro);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM pedido ORDER BY fecha_entrega ASC");
}

// Procesar pago completo si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pago_completo'])) {
    $id_pedido = $_POST['id_pedido'];
    $falta_pagar = $_POST['falta_pagar'];
    
    // Actualizar el pago en la base de datos
    $stmt = $conn->prepare("UPDATE pedido SET cantidad_pagada = cantidad_pagada + ? WHERE id = ?");
    $stmt->bind_param("di", $falta_pagar, $id_pedido);
    $stmt->execute();
    
    // Recargar la página para ver los cambios
    header("Location: listar_pedidos.php" . ($filtro ? "?estado=$filtro" : ""));
    exit;
}

function siguienteEstado($estado) {
    if ($estado == "En proceso") return "Listo";
    if ($estado == "Listo") return "Enviado";
    return "Enviado";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .pago-completo-btn {
            background-color: #28a745;
            color: white;
        }
        .pago-completo-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body class="container py-4">

<h2 class="mb-4">Pedidos</h2>

<!-- Botones de filtro -->
<div class="mb-4">
    <a href="listar_pedidos.php" class="btn btn-outline-secondary <?= $filtro == '' ? 'active' : '' ?>">Todos</a>
    <a href="listar_pedidos.php?estado=En proceso" class="btn btn-outline-warning <?= $filtro == 'En proceso' ? 'active' : '' ?>">En proceso</a>
    <a href="listar_pedidos.php?estado=Listo" class="btn btn-outline-info <?= $filtro == 'Listo' ? 'active' : '' ?>">Listo</a>
    <a href="listar_pedidos.php?estado=Enviado" class="btn btn-outline-success <?= $filtro == 'Enviado' ? 'active' : '' ?>">Enviado</a>
</div>

<a href="crear_pedido.php" class="btn btn-primary mb-4">Nuevo Pedido</a>
<a href="gananciasG.php" class="btn btn-info mb-4 ms-2">Ver Ganancias</a>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
<?php if ($result->num_rows > 0): ?>
<?php while ($row = $result->fetch_assoc()): 
    $falta = $row['valor_ramo'] - $row['cantidad_pagada'];
    $estadoColor = $row['estado'] == "En proceso" ? "warning" : ($row['estado'] == "Listo" ? "info" : "success");
?>
    <div class="col">
        <div class="card border-<?= $estadoColor ?> shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['nombre_cliente']) ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">

                    <a href="https://wa.me/57<?= preg_replace('/\D/', '', $row['celular']) ?>?text=<?= urlencode('Hola, su pedido está listo. ¿Prefiere que se lo enviemos o desea recogerlo personalmente?') ?>"
                        target="_blank">
                        <?= htmlspecialchars($row['celular']) ?>
                    </a>

                </h6>
                <p class="card-text">
                    <strong>Dirección:</strong> <?= htmlspecialchars($row['direccion']) ?><br>
                    <strong>Fecha de entrega:</strong> <?= htmlspecialchars($row['fecha_entrega']) ?><br>
                    <strong>Valor:</strong> $<?= number_format($row['valor_ramo'], 2) ?><br>
                    <strong>Pagado:</strong> $<?= number_format($row['cantidad_pagada'], 2) ?><br>
                    <strong>Falta por pagar:</strong> 
                    <span class="<?= $falta > 0 ? 'text-danger' : 'text-success' ?>">
                        $<?= number_format($falta, 2) ?>
                    </span><br>
                    <strong>Estado:</strong> 
                    <span class="badge bg-<?= $estadoColor ?>"><?= $row['estado'] ?></span>
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="cambiar_estado.php?id=<?= $row['id'] ?>&estado=<?= siguienteEstado($row['estado']) ?>" class="btn btn-outline-primary btn-sm">
                        Pasar a <?= siguienteEstado($row['estado']) ?>
                    </a>
                    
                    <?php if ($falta > 0): ?>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="id_pedido" value="<?= $row['id'] ?>">
                        <input type="hidden" name="falta_pagar" value="<?= $falta ?>">
                        <button type="submit" name="pago_completo" class="btn btn-sm pago-completo-btn">
                            Pago Completo
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <a href="eliminar_pedido.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar este pedido?')">
                        Eliminar
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>
<?php else: ?>
    <p class="text-muted">No hay pedidos para este estado.</p>
<?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Confirmación para pago completo
document.querySelectorAll('[name="pago_completo"]').forEach(button => {
    button.addEventListener('click', function(e) {
        if (!confirm('¿Marcar este pago como completo? Se sumará $' + button.parentElement.querySelector('[name="falta_pagar"]').value + ' al monto pagado.')) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>