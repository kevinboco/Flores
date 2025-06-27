<?php
include 'conexion.php';
date_default_timezone_set('America/Bogota');

$fecha = date('Y-m-d H:i:s');
$conn->query("INSERT INTO visitas_catalogo (fecha) VALUES ('$fecha')");

// Obtener categorías únicas
$sql = "SELECT DISTINCT categoria FROM catalogo_ramos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Flores Personalizadas | Ramos mágicos en Melany Variedades La Guajira</title>
  <meta name="description" content="Descubre ramos de flores únicos con mariposas, luces y detalles mágicos. Entregas en La Guajira. Hechos con amor en Melany Variedades.">
  <meta name="keywords" content="flores, ramos, rosas, regalos personalizados, La Guajira, Melany Variedades, Maicao, detalles mágicos, flores con luces, ramos con mariposas">
  <link rel="canonical" href="https://melany-variedades.shop/flores/" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- AOS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: linear-gradient(135deg, #f9f1f7, #ffe9f0);
      color: #333;
    }

    .titulo-magico {
      font-family: 'Fredoka', sans-serif;
      text-align: center;
      margin-top: 60px;
      font-size: 3.2rem;
      font-weight: 700;
      background: linear-gradient(to right, #e11d48, #db2777, #9333ea, #7c3aed);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      position: relative;
      animation: fadeInUp 1s ease-out both;
    }

    .blob {
      width: 60px;
      height: 60px;
      background: linear-gradient(to right, #e11d48, #7c3aed);
      border-radius: 40% 60% 60% 40% / 50% 50% 60% 40%;
      display: inline-block;
      margin: 0 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
      animation: float 4s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-12px); }
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .decoracion {
      text-align: center;
      font-size: 2rem;
      color: #f43f5e;
      animation: pulse 2s infinite;
      margin-bottom: 10px;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.1); opacity: 0.7; }
    }

    .container {
      column-count: 2;
      column-gap: 30px;
      padding: 40px;
      max-width: 1200px;
      margin: auto;
    }

    @media (min-width: 768px) {
      .container {
        column-count: 3;
      }
    }

    .card {
      background: white;
      border-radius: 20px;
      margin-bottom: 30px;
      display: inline-block;
      width: 100%;
      box-shadow: 0 10px 20px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
      overflow: hidden;
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-6px);
    }

    .card img {
      width: 100%;
      height: 220px;
      display: block;
      object-fit: cover;
    }

    .card-content {
      padding: 20px;
      text-align: center;
    }

    .card-content h3 {
      margin: 10px 0;
      font-size: 20px;
      color: #d63384;
      text-transform: capitalize;
    }

    .card-content button {
      background-color: #d63384;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 12px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .card-content button:hover {
      background-color: #b3246e;
    }

    .frase-magica {
      max-width: 1000px;
      margin: 40px auto;
      padding: 30px 50px;
      text-align: center;
      font-size: 1.7rem;
      color: #3b3b3b;
      background: linear-gradient(90deg, #fde5f3, #fbeeff);
      border-radius: 100px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
      font-family: 'Poppins', sans-serif;
      line-height: 1.6;
      border: 1px solid rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(6px);
      animation: fadeUp 1s ease-out both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="decoracion">✨</div>
<div style="text-align: center;">
  <span class="blob"></span>
  <span class="titulo-magico">Categorías<br>Mágicas</span>
  <span class="blob"></span>
</div>
<div class="decoracion">✨</div>

<div class="frase-magica" data-aos="fade-in" data-aos-delay="100">
  Descubre un universo de belleza floral donde cada pétalo<br>
  cuenta una historia única y mágica
</div>

<div class="container">
  <!-- Tarjeta "Ver Todos" -->
  <div class="card" data-aos="fade-up" onclick="location.href='ver_categoria.php'">
    <img src="https://images.pexels.com/photos/931162/pexels-photo-931162.jpeg" alt="Ver Todos" data-aos="zoom-in">
    <div class="card-content">
      <h3 data-aos="fade-right" data-aos-delay="100">Ver Todos</h3>
      <button data-aos="fade-up" data-aos-delay="200">Ver más</button>
    </div>
  </div>

  <?php while($row = $result->fetch_assoc()):
    $categoria = htmlspecialchars($row['categoria']);

    // Buscar nombre de imagen real asociada
    $sqlImg = "SELECT imagen FROM catalogo_ramos WHERE categoria = ? AND imagen IS NOT NULL AND imagen != '' LIMIT 1";
    $stmt = $conn->prepare($sqlImg);
    $stmt->bind_param("s", $categoria);
    $stmt->execute();
    $stmt->bind_result($imgNombre);
    $stmt->fetch();
    $stmt->close();

    // Construir ruta de imagen
    if ($imgNombre && file_exists("uploads/" . $imgNombre)) {
      $imgPath = "uploads/" . $imgNombre;
    } else {
      $imgPath = "uploads/default.jpg";
    }
  ?>
    <div class="card" data-aos="fade-up" onclick="location.href='ver_categoria.php?categoria=<?= urlencode($categoria) ?>'">
      <img src="<?= $imgPath ?>" alt="<?= $categoria ?>" data-aos="zoom-in">
      <div class="card-content">
        <h3 data-aos="fade-right" data-aos-delay="100"><?= $categoria ?></h3>
        <button data-aos="fade-up" data-aos-delay="200">Ver más</button>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<!-- AOS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000, once: true });
</script>

</body>
</html>
