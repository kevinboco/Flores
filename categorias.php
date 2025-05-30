<?php
include 'conexion.php';
$sql = "SELECT DISTINCT categoria FROM catalogo_ramos";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Categorías de Ramos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- AOS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #f2f2f2;
    }
    h1 {
      text-align: center;
      margin-top: 30px;
    }
    .container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 20px;
      padding: 40px;
      max-width: 1200px;
      margin: auto;
    }
    .card {
      background: white;
      padding: 30px 20px;
      text-align: center;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
      font-size: 18px;
      font-weight: bold;
      text-transform: capitalize;
    }
    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 12px 24px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>

  <h1>Categorías de Ramos</h1>
  <div class="container">
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="card" data-aos="zoom-in" onclick="window.location.href='ver_categoria.php?categoria=<?= urlencode($row['categoria']) ?>'">
        <?= htmlspecialchars($row['categoria']) ?>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- AOS JS -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 800,
      once: true
    });
  </script>
</body>
</html>
