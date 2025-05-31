<?php
include 'conexion.php';
$categoria = $_GET['categoria'] ?? '';

$stmt = $conn->prepare("SELECT * FROM catalogo_ramos WHERE categoria = ?");
$stmt->bind_param("s", $categoria);
$stmt->execute();
$result = $stmt->get_result();
$telefono = "573215116044";

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
        height: auto;
        object-fit: contain;
        max-height: 300px; /* opcional: evita que se estiren demasiado las imágenes verticales */
        display: block;
        margin: auto;
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
    @keyframes pulse {
  0% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.6);
  }
  70% {
    transform: scale(1.05);
    box-shadow: 0 0 0 10px rgba(37, 211, 102, 0);
  }
  100% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
  }
}

.btn-whatsapp {
  display: inline-block;
  margin-top: 10px;
  padding: 10px 18px;
  background-color: #25D366;
  color: white;
  font-weight: bold;
  border: none;
  border-radius: 8px;
  text-decoration: none;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  animation: pulse 2s infinite;
}

.btn-whatsapp:hover {
  transform: scale(1.08);
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
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
          <?php
            $telefono = "573215116044";
            $titulo = urlencode($row['titulo']);
            $mensaje = urlencode("¡Quiero este producto! $titulo");
            $link_whatsapp = "https://wa.me/$telefono?text=$mensaje";
          ?>
          <a href="<?= $link_whatsapp ?>" target="_blank" class="btn-whatsapp">¡Lo quiero!</a>
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
