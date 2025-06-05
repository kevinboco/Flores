<?php
include 'conexion.php';

// Consultar la cantidad de pedidos por fecha
$eventos = [];
$sql = "SELECT fecha_entrega, COUNT(*) as cantidad FROM pedido GROUP BY fecha_entrega";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $eventos[] = [
        'title' => $row['cantidad'] . ' pedidos',
        'start' => $row['fecha_entrega'],
        'url' => 'ver_pedidos_dia.php?fecha=' . $row['fecha_entrega']
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Calendario de Pedidos</title>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        h1 {
            text-align: center;
            margin-bottom: 2rem;
            color: #343a40;
            font-weight: 700;
        }
        #calendario {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 16px rgb(0 0 0 / 0.1);
        }
        /* Cambiar colores de días seleccionados y hover */
        .fc-daygrid-day:hover {
            background-color: #e9f5ff;
            cursor: pointer;
        }
        .fc-daygrid-event {
            background-color: #0d6efd !important; /* Bootstrap primary */
            border: none !important;
            border-radius: 6px;
            padding: 3px 6px;
            font-weight: 600;
            color: white !important;
            box-shadow: 0 2px 4px rgb(13 110 253 / 0.4);
            transition: background-color 0.3s ease;
        }
        .fc-daygrid-event:hover {
            background-color: #0b5ed7 !important;
            box-shadow: 0 4px 8px rgb(11 94 215 / 0.6);
        }
        /* Cambiar encabezado */
        .fc-toolbar-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0d6efd;
        }
        /* Botones del toolbar */
        .fc-button {
            background-color: #0d6efd;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            padding: 6px 12px;
            box-shadow: 0 3px 6px rgb(13 110 253 / 0.4);
            transition: background-color 0.3s ease;
        }
        .fc-button:hover, .fc-button:focus {
            background-color: #0b5ed7;
            box-shadow: 0 6px 12px rgb(11 94 215 / 0.6);
            outline: none;
        }
        /* Quitar borde focus molesto */
        .fc-button:focus-visible {
            outline: 2px solid #0b5ed7;
            outline-offset: 2px;
        }
    </style>
</head>
<body class="container py-4">

    <h1>Calendario de Pedidos</h1>
    <div id="calendario"></div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendario');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                events: <?= json_encode($eventos) ?>,
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // Previene comportamiento por defecto
                    if (info.event.url) {
                        window.location.href = info.event.url; // Redirige a vista de pedidos
                    }
                },
                dayMaxEvents: true, // Mostrar "+X más" si hay muchos eventos
                navLinks: true, // Click en días/semana para navegar (útil si quieres)
            });
            calendar.render();
        });
    </script>
</body>
</html>
