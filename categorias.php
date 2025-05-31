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

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <!-- Iconos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: linear-gradient(135deg, #f9f1f7, #ffe9f0);
      color: #333;
    }

    h1 {
      text-align: center;
      font-size: 32px;
      margin: 40px 0 20px;
      color: #d63384;
    }

    .container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 25px;
      padding: 30px;
      max-width: 1200px;
      margin: auto;
    }

    .card {
      background: white;
      padding: 30px 20px;
      text-align: center;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
      font-size: 18px;
      font-weight: 600;
      text-transform: capitalize;
      position: relative;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    }

    .card i {
      font-size: 36px;
      color: #d63384;
      margin-bottom: 10px;
    }

    .card-text {
      margin-top: 10px;
      color: #444;
    }

    @media screen and (max-width: 600px) {
      h1 {
        font-size: 24px;
        margin: 20px;
      }

      .card {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>

  <h1>Categorías de Ramos</h1>

  <div class="container">
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="card" data-aos="zoom-in" onclick="window.location.href='ver_categoria.php?categoria=<?= urlencode($row['categoria']) ?>'">
        <i class="bi bi-flower1"></i>
        <div class="card-text"><?= htmlspecialchars($row['categoria']) ?></div>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- AOS JS -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 800, once: true });
  </script>

</body>
</html>
