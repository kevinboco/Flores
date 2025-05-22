<?php
// Primero procesamos los datos PHP
include('conexion.php');

$sql = "SELECT fecha_entrega, SUM(cantidad_pagada) AS total_vendido 
        FROM pedido 
        GROUP BY fecha_entrega 
        ORDER BY fecha_entrega";

$resultado = $conn->query($sql);
$datos = [];
while ($fila = $resultado->fetch_assoc()) {
    $datos[] = $fila;
}

// Si es una petición AJAX (fetch), devolvemos solo JSON
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($datos);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gráfico de Ventas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 80%;
            max-width: 900px;
            margin: 20px auto;
        }
        body {
            background-image: url('uploads/fondo.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
 
<body>
    <div class="info-box">
        <strong>Nota:</strong> Esto son los pedidos en general no importa si esta en  "Enviado,proceso o listo ".
    </div>
    
    <div class="chart-container">
        <h2>Ventas por Fecha</h2>
        <canvas id="graficoVentas"></canvas>
    </div>

    <script>
    // Convertimos los datos PHP a JavaScript directamente
    const datosVentas = <?php echo json_encode($datos); ?>;
    
    document.addEventListener('DOMContentLoaded', function() {
        if(datosVentas.length === 0) {
            document.getElementById('graficoVentas').insertAdjacentHTML('afterend', 
                '<p style="color:red">No hay datos disponibles para mostrar</p>');
            return;
        }

        const ctx = document.getElementById('graficoVentas').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: datosVentas.map(item => item.fecha_entrega),
                datasets: [{
                    label: 'Total Vendido ($)',
                    data: datosVentas.map(item => parseFloat(item.total_vendido)),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    });
    </script>
    <a href="listar_pedidos.php">
  <button>atras </button>
  <a href="gananciastoporfiltroF.php">
  <button>ir a filtros de fechas de pedidos enviados y completados </button>
</body>
</html>