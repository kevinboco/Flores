<?php
session_start();
require 'conexion.php';
function pedidosEscape($value) { return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function pedidosParametro($key) { return isset($_GET[$key]) && is_string($_GET[$key]) ? trim($_GET[$key]) : ''; }
$fecha_filtro = pedidosParametro('fecha');
$fecha = DateTime::createFromFormat('!Y-m-d', $fecha_filtro);
$fecha_invalida = $fecha_filtro !== '' && (!$fecha || $fecha->format('Y-m-d') !== $fecha_filtro);
if ($fecha_invalida) { $fecha_filtro = ''; }
$estado_filtro = pedidosParametro('estado');
$estados = ['En proceso', 'Listo', 'Enviado'];
if (!in_array($estado_filtro, $estados, true)) { $estado_filtro = ''; }
$busqueda = pedidosParametro('q');
if (empty($_SESSION['pedidos_csrf'])) { $_SESSION['pedidos_csrf'] = bin2hex(random_bytes(32)); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf'] ?? '';
    $id = filter_var($_POST['id_pedido'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if (!is_string($token) || !hash_equals($_SESSION['pedidos_csrf'], $token) || !$id) {
        $error = 'La solicitud no es válida. Recarga la página e intenta nuevamente.';
    } else {
        try {
            if (isset($_POST['pago_completo'])) {
                // El saldo se obtiene de la base de datos. Repetir la solicitud no suma otro pago.
                $stmt = $conn->prepare('UPDATE pedido SET cantidad_pagada = valor_ramo WHERE id = ? AND COALESCE(cantidad_pagada, 0) < valor_ramo');
                $stmt->bind_param('i', $id);
            } elseif (isset($_POST['avanzar_estado'])) {
                $stmt = $conn->prepare("UPDATE pedido SET estado = CASE estado WHEN 'En proceso' THEN 'Listo' WHEN 'Listo' THEN 'Enviado' ELSE estado END WHERE id = ? AND estado = ?");
                $anterior = $_POST['estado_actual'] ?? '';
                if (!is_string($anterior) || !in_array($anterior, $estados, true)) { throw new RuntimeException('Estado inválido'); }
                $stmt->bind_param('is', $id, $anterior);
            } else { throw new RuntimeException('Acción inválida'); }
            if (!$stmt->execute()) { throw new RuntimeException('No se pudo actualizar'); }
            $_SESSION['pedidos_aviso'] = $stmt->affected_rows > 0 ? 'Pedido actualizado correctamente.' : 'El pedido ya estaba actualizado o no existe. Revisa la lista.';
            $stmt->close();
            header('Location: ver_pedidos_dia.php?' . http_build_query(['fecha' => $fecha_filtro, 'estado' => $estado_filtro, 'q' => $busqueda]), true, 303);
            exit;
        } catch (Exception $e) { $error = 'No se pudo actualizar el pedido. Intenta nuevamente.'; }
    }
}
$aviso = $_SESSION['pedidos_aviso'] ?? '';
unset($_SESSION['pedidos_aviso']);
$conteo = array_fill_keys($estados, 0);
// Los contadores corresponden al día seleccionado, antes de filtrar por estado o búsqueda.
$stmt = $conn->prepare("SELECT estado, COUNT(*) AS cantidad FROM pedido WHERE (? = '' OR fecha_entrega = ?) GROUP BY estado");
$stmt->bind_param('ss', $fecha_filtro, $fecha_filtro);
$stmt->execute();
$result_conteo = $stmt->get_result();
while ($r = $result_conteo->fetch_assoc()) { if (isset($conteo[$r['estado']])) { $conteo[$r['estado']] = (int) $r['cantidad']; } }
$stmt->close();
$patron = '%' . $busqueda . '%';
$stmt = $conn->prepare("SELECT * FROM pedido WHERE (? = '' OR fecha_entrega = ?) AND (? = '' OR estado = ?) AND (? = '' OR nombre_cliente LIKE ? OR celular LIKE ? OR direccion LIKE ?) ORDER BY fecha_entrega ASC, id ASC");
$stmt->bind_param('ssssssss', $fecha_filtro, $fecha_filtro, $estado_filtro, $estado_filtro, $busqueda, $patron, $patron, $patron);
$stmt->execute();
$result = $stmt->get_result();
$pedidos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$total = $pagado = $pendiente = 0;
foreach ($pedidos as $pedido) {
    $total += (float) $pedido['valor_ramo'];
    $pagado += (float) ($pedido['cantidad_pagada'] ?? 0);
    $pendiente += max(0, (float) $pedido['valor_ramo'] - (float) ($pedido['cantidad_pagada'] ?? 0));
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pedidos del día <?= pedidosEscape($fecha_filtro) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f7f3f5; color: #302530;
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
        .card { border-radius: 18px; background: #fff; }
        .card-text { overflow-wrap: anywhere; }
        .main-content { padding-top: 2rem; }
        #sidebar { visibility: hidden; }
        #sidebar.show { visibility: visible; }
        .summary { background: #fff; border: 1px solid #e8dce4; border-radius: 14px; padding: 1rem; }
        .summary strong { display: block; font-size: 1.35rem; }
        @media (max-width: 767px) {
            .with-sidebar { margin-left: 0; }
            #sidebar { width: min(290px, 90vw); box-shadow: 0 0 0 100vmax #0006; }
            .dock-panel { gap: .25rem; max-width: 100%; }
            .dock-item { width: 34px; flex-shrink: 1; }
        }
        @media print { #sidebar, #toggleSidebar, .dock-outer, .card form, .card .btn, .filter-form { display: none !important; } .main-content { margin: 0; padding: 0; } .card { break-inside: avoid; } }
    </style>
</head>

<body class="container py-4">
    <?php include 'texto circular.php'; include 'nav.php'; ?>

    <!-- Toggle Button -->
    <button id="toggleSidebar" class="btn btn-primary" aria-controls="sidebar" aria-expanded="false">
        ☰ Filtros
    </button>

    <!-- Sidebar -->
    <div id="sidebar" class="bg-light shadow">
        <h5>PEDIDOS</h5>
        <div class="d-grid gap-2">
            <a href="listar_pedidos.php" class="btn btn-outline-secondary">
                Vista general
            </a>
            <a href="ver_pedidos_dia.php?<?= pedidosEscape(http_build_query(['fecha' => $fecha_filtro, 'estado' => 'En proceso', 'q' => $busqueda])) ?>"
                class="btn btn-outline-warning">
                En proceso (<?= $conteo['En proceso'] ?>)
            </a>
            <a href="ver_pedidos_dia.php?<?= pedidosEscape(http_build_query(['fecha' => $fecha_filtro, 'estado' => 'Listo', 'q' => $busqueda])) ?>"
                class="btn btn-outline-info">
                Listo (<?= $conteo['Listo'] ?>)
            </a>
            <a href="ver_pedidos_dia.php?<?= pedidosEscape(http_build_query(['fecha' => $fecha_filtro, 'estado' => 'Enviado', 'q' => $busqueda])) ?>"
                class="btn btn-outline-success">
                Enviado (<?= $conteo['Enviado'] ?>)
            </a>
        </div>
        <hr>
        <h6>Filtrar por día</h6>
        <form method="get" class="mb-3 filter-form">
            <label for="fecha" class="form-label">Fecha de entrega</label>
            <input type="date" id="fecha" name="fecha" class="form-control" value="<?= pedidosEscape($fecha_filtro) ?>">
            <label for="estado" class="form-label mt-2">Estado</label>
            <select id="estado" name="estado" class="form-select"><option value="">Todos</option>
            <?php foreach ($estados as $estado): ?><option value="<?= pedidosEscape($estado) ?>" <?= $estado_filtro === $estado ? 'selected' : '' ?>><?= pedidosEscape($estado) ?></option><?php endforeach; ?></select>
            <label for="q" class="form-label mt-2">Cliente, teléfono o dirección</label>
            <input type="search" id="q" name="q" class="form-control" value="<?= pedidosEscape($busqueda) ?>">
            <button type="submit" class="btn btn-primary mt-2 w-100">Aplicar filtros</button>
            <a href="ver_pedidos_dia.php" class="btn btn-outline-secondary mt-2 w-100">Limpiar filtros</a>
        </form>
    </div>

    <div class="main-content">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h1 class="h3 mb-0"><?= $fecha_filtro ? 'Pedidos del ' . pedidosEscape(date('d/m/Y', strtotime($fecha_filtro))) : 'Todos los pedidos' ?></h1>
            <a href="crear_pedido.php" class="btn btn-primary">Nuevo pedido</a>
        </div>
        <?php if ($fecha_invalida): ?><p class="alert alert-warning">La fecha no es válida. Se muestran todos los días.</p><?php endif; ?>
        <?php if ($error): ?><p class="alert alert-danger" role="alert"><?= pedidosEscape($error) ?></p><?php endif; ?>
        <?php if ($aviso): ?><p class="alert alert-success" role="status"><?= pedidosEscape($aviso) ?></p><?php endif; ?>
        <div class="row g-3 mb-4" aria-label="Resumen de pedidos filtrados">
            <?php foreach (['Pedidos visibles' => count($pedidos), 'Valor total' => '$' . number_format($total, 2, ',', '.'), 'Pagado' => '$' . number_format($pagado, 2, ',', '.'), 'Por cobrar' => '$' . number_format($pendiente, 2, ',', '.')] as $label => $value): ?>
            <div class="col-6 col-lg-3"><div class="summary"><?= pedidosEscape($label) ?><strong><?= pedidosEscape($value) ?></strong></div></div>
            <?php endforeach; ?>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if (count($pedidos) > 0): ?>
                <?php foreach ($pedidos as $row):
                    $falta = max(0, (float) $row['valor_ramo'] - (float) ($row['cantidad_pagada'] ?? 0));
                    $telefono = preg_replace('/\D/', '', (string) ($row['celular'] ?? ''));
                    if (strlen($telefono) === 10) { $telefono = '57' . $telefono; }
                    $estadoColor = $row['estado'] == "En proceso" ? "warning" : ($row['estado'] == "Listo" ? "info" : "success");
                ?>
                    <div class="col">
                        <div class="card border-<?= $estadoColor ?> shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= pedidosEscape($row['nombre_cliente']) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <a href="https://wa.me/<?= pedidosEscape($telefono) ?>?text=<?= urlencode('Hola, su pedido está listo. ¿Prefiere que se lo enviemos o desea recogerlo personalmente?') ?>"
                                        target="_blank" rel="noopener noreferrer">
                                        <?= pedidosEscape($row['celular']) ?>
                                    </a>
                                </h6>
                                <p class="card-text">
                                    <strong>Dirección:</strong> <?= pedidosEscape($row['direccion']) ?><br>
                                    <strong>Fecha de entrega:</strong> <?= pedidosEscape($row['fecha_entrega']) ?><br>
                                    <strong>Valor:</strong> $<?= number_format((float) $row['valor_ramo'], 2, ',', '.') ?><br>
                                    <strong>Pagado:</strong> $<?= number_format((float) ($row['cantidad_pagada'] ?? 0), 2, ',', '.') ?><br>
                                    <strong>Descripción:</strong> <?= pedidosEscape($row['descripcion'] ?? '') ?><br>
                                    <strong>Falta por pagar:</strong>
                                    <span class="<?= $falta > 0 ? 'text-danger' : 'text-success' ?>">
                                        $<?= number_format($falta, 2, ',', '.') ?>
                                    </span><br>
                                    <strong>Estado:</strong>
                                    <span class="badge bg-<?= $estadoColor ?>"><?= pedidosEscape($row['estado']) ?></span>
                                </p>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php if ($row['estado'] !== 'Enviado'): ?>
                                    <form method="post">
                                        <input type="hidden" name="csrf" value="<?= pedidosEscape($_SESSION['pedidos_csrf']) ?>">
                                        <input type="hidden" name="id_pedido" value="<?= (int) $row['id'] ?>">
                                        <input type="hidden" name="estado_actual" value="<?= pedidosEscape($row['estado']) ?>">
                                        <button type="submit" name="avanzar_estado" class="btn btn-outline-primary btn-sm">Pasar a <?= pedidosEscape(siguienteEstado($row['estado'])) ?></button>
                                    </form>
                                    <?php endif; ?>

                                    <?php if ($falta > 0): ?>
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="id_pedido" value="<?= $row['id'] ?>">
                                            <input type="hidden" name="csrf" value="<?= pedidosEscape($_SESSION['pedidos_csrf']) ?>">
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
                                            class="btn btn-outline-dark btn-sm" target="_blank" rel="noopener noreferrer">
                                            Pedir domicilio
                                        </a>

                                        <a href="recordatoio.php?to=<?= pedidosEscape($telefono) ?>&body=<?= urlencode("hola ,Ya puede pasar por su pedido") ?>"
                                            class="btn btn-outline-success btn-sm" target="_blank" rel="noopener noreferrer">
                                            Avisar que está listo
                                        </a>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No hay pedidos que coincidan con los filtros. Puedes cambiar la fecha o limpiar los filtros.</p>
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
            toggleBtn.setAttribute('aria-expanded', sidebar.classList.contains('show'));
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                sidebar.classList.remove('show');
                mainContent.classList.remove('with-sidebar');
                toggleBtn.setAttribute('aria-expanded', 'false');
                toggleBtn.focus();
            }
        });
    </script>
</body>

</html>

