<?php include 'texto circular.php'; ?>
<?php include 'nav.php'; ?>
<?php

include 'conexion.php';

// Contar pedidos por estado
$conteo = [
    'En proceso' => 0,
    'Listo' => 0,
    'Enviado' => 0
];

$sql_conteo = "SELECT estado, COUNT(*) as cantidad FROM pedido GROUP BY estado";
$result_conteo = $conn->query($sql_conteo);
while ($row_conteo = $result_conteo->fetch_assoc()) {
    $conteo[$row_conteo['estado']] = $row_conteo['cantidad'];
}

// Obtener la fecha desde GET para filtrar pedidos de ese día
$fecha_filtro = $_GET['fecha'] ?? '';

if ($fecha_filtro !== '') {
    $stmt = $conn->prepare("SELECT * FROM pedido WHERE fecha_entrega = ? ORDER BY fecha_entrega ASC");
    $stmt->bind_param("s", $fecha_filtro);
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

    // Recargar la página para ver los cambios, preservando fecha
    header("Location: ver_pedidos_dia.php" . ($fecha_filtro ? "?fecha=$fecha_filtro" : ""));
    exit;
}

function siguienteEstado($estado)
{
    if ($estado == "En proceso")
        return "Listo";
    if ($estado == "Listo")
        return "Enviado";
    return "Enviado";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pedidos del día <?= htmlspecialchars($fecha_filtro) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('uploads/63a7cb6a-ba55-4583-9daf-971cd26453e5.jpeg') no-repeat center center;
            background-size: cover;
            padding-left: 0;
            transition: padding-left 0.3s ease;
        }

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

        /* Botón toggle fijo, siempre visible y FUERA del sidebar */
        #toggleSidebar {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1100;
        }

        /* Sidebar que se desliza desde la izquierda */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background-color: #f8f9fa;
            padding: 4rem 1rem 1rem 1rem;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        /* Clase que activa la visibilidad del sidebar */
        #sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            padding: 5rem 1rem 1rem 1rem;
            transition: margin-left 0.3s ease;
        }

        .with-sidebar {
            margin-left: 250px;
        }
    </style>
</head>

<body class="container py-4">

    <!-- Toggle Button -->
    <button id="toggleSidebar" class="btn btn-primary">
        ☰ Filtros
    </button>

    <!-- Sidebar -->
    <div id="sidebar" class="bg-light shadow">
        <h5>PEDIDOS</h5>
        <div class="d-grid gap-2">
            <a href="listar_pedidos.php" class="btn btn-outline-secondary">
                Vista general
            </a>
            <a href="listar_pedidos.php?estado=En proceso"
                class="btn btn-outline-warning">
                En proceso (<?= $conteo['En proceso'] ?>)
            </a>
            <a href="listar_pedidos.php?estado=Listo"
                class="btn btn-outline-info">
                Listo (<?= $conteo['Listo'] ?>)
            </a>
            <a href="listar_pedidos.php?estado=Enviado"
                class="btn btn-outline-success">
                Enviado (<?= $conteo['Enviado'] ?>)
            </a>
        </div>
        <hr>
        <h6>Filtrar por día</h6>
        <form method="get" class="mb-3">
            <input type="date" name="fecha" class="form-control" value="<?= htmlspecialchars($fecha_filtro) ?>">
            <button type="submit" class="btn btn-primary mt-2 w-100">Filtrar</button>
        </form>
    </div>

    <div class="main-content">

        <h3>Pedidos del día <?= htmlspecialchars($fecha_filtro) ?></h3>

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
                                    <strong>Descripción:</strong> <?= htmlspecialchars($row['descripcion']) ?><br>
                                    <strong>Falta por pagar:</strong>
                                    <span class="<?= $falta > 0 ? 'text-danger' : 'text-success' ?>">
                                        $<?= number_format($falta, 2) ?>
                                    </span><br>
                                    <strong>Estado:</strong>
                                    <span class="badge bg-<?= $estadoColor ?>"><?= $row['estado'] ?></span>
                                </p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="cambiar_estado.php?id=<?= $row['id'] ?>&estado=<?= siguienteEstado($row['estado']) ?>"
                                        class="btn btn-outline-primary btn-sm">
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

                                    <a href="eliminar_pedido.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('¿Eliminar este pedido?')">
                                        Eliminar
                                    </a>

                                    <a href="editar_pedido.php?id=<?= $row['id'] ?>" class="btn btn-outline-secondary btn-sm">
                                        Editar
                                    </a>

                                    <?php if ($row['estado'] == 'Listo'): ?>
                                        <a href="recordatoio.php?to=573043859242&body=<?= urlencode('Hola, para pedir un domicilio estoy en la cra 25 #18-40 y el pedido va para ' . $row['direccion']) ?>"
                                            class="btn btn-outline-dark btn-sm" target="_blank">
                                            Pedir domicilio
                                        </a>

                                        <a href="recordatoio.php?to=57<?= preg_replace('/\D/', '', $row['celular']) ?>&body=<?= urlencode("hola ,Ya puede pasar por su pedido") ?>"
                                            class="btn btn-outline-success btn-sm" target="_blank">
                                            Avisar que está listo
                                        </a>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">No hay pedidos para esta fecha.</p>
            <?php endif; ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Confirmación para pago completo
        document.querySelectorAll('[name="pago_completo"]').forEach(button => {
            button.addEventListener('click', function (e) {
                if (!confirm('¿Marcar pago como completo?')) {
                    e.preventDefault();
                }
            });
        });

        // Toggle sidebar
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('with-sidebar');
        });
    </script>

</body>

</html>
