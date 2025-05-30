<?php
include 'conexion.php';
$categoria = $_GET['categoria'] ?? '';

$stmt = $conn->prepare("SELECT * FROM catalogo_ramos WHERE categoria = ?");
$stmt->bind_param("s", $categoria);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ramos - <?= htmlspecialchars($categoria) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- AOS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #ffffff;
    }
    .header {
      padding: 20px;
      background: #f0f0f0;
      text-align: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .header a {
      text-decoration: none;
      color: #007BFF;
      margin-top: 10px;
      display: inline-block;
    }
    .container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 20px;
      padding: 30px;
      max-width: 1200px;
      margin: auto;
    }
    .card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .info {
      padding: 15px;
    }
    .info h3 {
      margin: 0 0 10px;
      font-size: 20px;
    }
    .info p {
      margin: 5px 0;
      font-size: 16px;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>Ramos de la categoría: <?= htmlspecialchars($categoria) ?></h1>
    <a href="categorias.php">← Volver a categorías</a>
  </div>

  <div class="container">
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="card" data-aos="fade-up">
        <img src="./uploads/<?= htmlspecialchars($row['imagen']) ?>" alt="<?= htmlspecialchars($row['titulo']) ?>">
        <div class="info">
          <h3><?= htmlspecialchars($row['titulo']) ?></h3>
          <p><strong>Precio:</strong> $<?= number_format($row['valor']) ?></p>
          <p><?= htmlspecialchars($row['description']) ?></p>
        </div>
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
