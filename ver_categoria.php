<?php
include 'conexion.php';
$categoria = $_GET['categoria'] ?? '';
$sql = "SELECT * FROM catalogo_ramos WHERE categoria = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $categoria);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Catálogo: <?= htmlspecialchars($categoria) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- AOS y Google Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: linear-gradient(to right, #fff0f6, #ffe5ec);
      color: #333;
    }

    /* Navbar de regreso */
    .navbar {
      display: flex;
      align-items: center;
      padding: 15px 20px;
      background-color: #fff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .navbar a {
      text-decoration: none;
      color: #d63384;
      font-weight: bold;
      font-size: 16px;
      display: flex;
      align-items: center;
      transition: color 0.3s;
    }

    .navbar a:hover {
      color: #a61e65;
    }

    .navbar a::before {
      content: "← ";
      margin-right: 5px;
    }

    h1 {
      text-align: center;
      margin: 30px 10px;
      color: #d63384;
      font-size: 30px;
    }

    .container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 30px;
      padding: 30px;
      max-width: 1200px;
      margin: auto;
    }

    .card {
      background: white;
      border-radius: 18px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      display: flex;
      flex-direction: column;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 28px rgba(0,0,0,0.12);
    }

    .card img {
      width: 100%;
      height: auto;
      object-fit: cover;
      aspect-ratio: 4/3;
    }

    .info {
      padding: 20px;
      flex: 1;
    }

    .info h3 {
      margin: 0 0 10px;
      font-size: 20px;
      color: #d63384;
    }

    .info p {
      margin: 5px 0;
      font-size: 15px;
    }

    .boton-whatsapp {
      background: #25D366;
      color: white;
      padding: 10px 16px;
      margin: 15px;
      text-align: center;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.3s, transform 0.3s;
      animation: pulse 2s infinite;
    }

    .boton-whatsapp:hover {
      background: #1ebe5c;
      transform: scale(1.05);
    }

    @keyframes pulse {
      0%, 100% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.5);
      }
      50% {
        box-shadow: 0 0 0 10px rgba(37, 211, 102, 0);
      }
    }
  </style>
</head>
<body>

  <div class="navbar">
    <a href="categorias.php">Volver a categorías</a>
  </div>

  <h1>Ramos: <?= htmlspecialchars($categoria) ?></h1>

  <div class="container">
    <?php while($row = $result->fetch_assoc()): 
      $titulo = htmlspecialchars($row['titulo']);
      $imagen = './uploads/' . htmlspecialchars($row['imagen']);
      $mensaje = urlencode("Quiero este producto: $titulo");
      $link = "https://wa.me/573215116044?text=$mensaje";
    ?>
      <div class="card" data-aos="fade-up">
        <img src="<?= $imagen ?>" alt="<?= $titulo ?>">
        <div class="info">
          <h3><?= $titulo ?></h3>
          <p><strong>Precio:</strong> $<?= number_format($row['valor']) ?></p>
          <p><?= htmlspecialchars($row['description']) ?></p>
        </div>
        <a class="boton-whatsapp" href="<?= $link ?>" target="_blank">💐 Lo quiero</a>
      </div>
    <?php endwhile; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>AOS.init({ duration: 800, once: true });</script>
</body>
</html>
