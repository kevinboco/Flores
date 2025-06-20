<?php
include 'conexion.php';

$result = $conn->query("SELECT * FROM visitas_catalogo ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Visitas al Catálogo</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f0f0f0; }
    h1 { color: #d63384; }
    table { border-collapse: collapse; width: 100%; background: white; }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
    th { background-color: #d63384; color: white; }
  </style>
</head>
<body>

<h1>Historial de ingresos al catálogo</h1>
<table>
  <tr>
    <th>#</th>
    <th>Fecha y hora</th>
  </tr>
  <?php
  $i = 1;
  while ($row = $result->fetch_assoc()):
  ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= $row['fecha'] ?></td>
    </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
