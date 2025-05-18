<?php
// Verificar si se debe mostrar JSON o HTML
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($is_ajax) {
    // Configuración para respuesta AJAX
    include('conexion.php');
    
    $fecha_inicio = isset($_REQUEST['fecha_inicio']) ? $_REQUEST['fecha_inicio'] : date('Y-m-01');
    $fecha_fin = isset($_REQUEST['fecha_fin']) ? $_REQUEST['fecha_fin'] : date('Y-m-t');

    $sql = "SELECT fecha_entrega, SUM(cantidad_pagada) AS total_vendido 
            FROM pedido 
            WHERE fecha_entrega BETWEEN '$fecha_inicio' AND '$fecha_fin'
            AND estado = 'Enviado'
            GROUP BY fecha_entrega 
            ORDER BY fecha_entrega";

    $resultado = $conn->query($sql);
    $datos = [];
    $total_general = 0;

    while ($fila = $resultado->fetch_assoc()) {
        $fila['total_vendido'] = (float)$fila['total_vendido'];
        $total_general += $fila['total_vendido'];
        $datos[] = $fila;
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'datos' => $datos,
        'total' => $total_general,
        'periodo' => ['inicio' => $fecha_inicio, 'fin' => $fecha_fin]
    ]);
    exit;
}

// Si no es AJAX, continuar con el HTML
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas - Solo Enviados</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .filtros { background: #f2f2f2; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .btn { padding: 8px 15px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        #grafico-container { margin-top: 30px; }
        .info-box { background: #e7f3fe; padding: 10px; border-left: 6px solid #2196F3; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="info-box">
        <strong>Nota:</strong> Este reporte solo muestra ganancias de pedidos con estado "Enviado".
    </div>
    
    <h1>Reporte de Ventas (Solo Enviados)</h1>
    
    <div class="filtros">
        <h2>Filtrar por Fecha</h2>
        <form id="formFiltro">
            <label for="fecha_inicio">Desde:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>
            
            <label for="fecha_fin">Hasta:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required>
            
            <button type="submit" class="btn">Filtrar</button>
        </form>
        <div id="error" style="color:red; margin-top:10px;"></div>
    </div>
    
    <div id="resultados">
        <h2>Total Vendido: $<span id="totalVendido">0.00</span></h2>
        <p>Periodo: <span id="periodoTexto"></span></p>
        
        <table id="tablaVentas">
            <thead>
                <tr>
                    <th>Fecha de Envío</th>
                    <th>Total Vendido</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        
        <div id="grafico-container">
            <canvas id="graficoVentas" width="800" height="400"></canvas>
        </div>
    </div>

    <script>
    let ventasChart = null;

    function inicializarGrafico(datos) {
        const ctx = document.getElementById('graficoVentas').getContext('2d');
        if(ventasChart) ventasChart.destroy();
        
        ventasChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: datos.map(item => item.fecha_entrega),
                datasets: [{
                    label: 'Total Vendido ($) - Solo Enviados',
                    data: datos.map(item => item.total_vendido),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    }

    document.getElementById('formFiltro').addEventListener('submit', function(e) {
        e.preventDefault();
        const inicio = document.getElementById('fecha_inicio').value;
        const fin = document.getElementById('fecha_fin').value;
        
        if (!inicio || !fin) {
            document.getElementById('error').textContent = 'Seleccione ambas fechas';
            return;
        }

        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `fecha_inicio=${inicio}&fecha_fin=${fin}`
        })
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            return response.json();
        })
        .then(data => {
            if (!data.success) throw new Error(data.error || 'Error en los datos');
            
            document.getElementById('totalVendido').textContent = data.total.toFixed(2);
            document.getElementById('periodoTexto').textContent = 
                `${new Date(data.periodo.inicio).toLocaleDateString('es-ES')} - ${new Date(data.periodo.fin).toLocaleDateString('es-ES')}`;
            
            const tbody = document.querySelector('#tablaVentas tbody');
            tbody.innerHTML = data.datos.map(item => `
                <tr>
                    <td>${new Date(item.fecha_entrega).toLocaleDateString('es-ES')}</td>
                    <td>$${item.total_vendido.toFixed(2)}</td>
                </tr>
            `).join('');
            
            tbody.innerHTML += `
                <tr style="font-weight:bold; background-color:#f2f2f2;">
                    <td>Total General (Solo Enviados)</td>
                    <td>$${data.total.toFixed(2)}</td>
                </tr>
            `;
            
            inicializarGrafico(data.datos);
        })
        .catch(error => {
            document.getElementById('error').textContent = error.message;
            console.error('Error:', error);
        });
    });
    </script>
    
    <a href="listar_pedidos.php" style="display:inline-block; margin-top:20px;">
        <button style="padding:8px 15px; background:#607d8b; color:white; border:none; border-radius:4px; cursor:pointer;">Atrás</button>
    </a>
</body>
</html>