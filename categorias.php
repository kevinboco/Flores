<?php
include 'conexion.php';
include 'texto circular.php';

$sql = "SELECT DISTINCT categoria FROM catalogo_ramos";
$result = $conn->query($sql);

// Leer imágenes locales desde carpeta uploads/
$carpeta = 'uploads/';
$imagenes_disponibles = glob($carpeta . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
shuffle($imagenes_disponibles); // barajamos para que no se repitan en orden
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Categorías de Ramos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- AOS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background-color: #eaf0f6;
      color: #333;
    }

    h1 {
      text-align: center;
      margin: 40px 20px 20px;
      color: #d63384;
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
  </style>
</head>
<body>

<h1 data-aos="fade-down">Categorías de Ramos</h1>

<div class="container">

  <!-- Tarjeta "Ver Todos" -->
  <div class="card" data-aos="fade-up" onclick="location.href='ver_categoria.php'">
    <img src="https://images.pexels.com/photos/931162/pexels-photo-931162.jpeg" alt="Ver Todos" data-aos="zoom-in">
    <div class="card-content">
      <h3 data-aos="fade-right" data-aos-delay="100">Ver Todos</h3>
      <button data-aos="fade-up" data-aos-delay="200">Ver más</button>
    </div>
  </div>

  <?php
  $i = 0;
  while($row = $result->fetch_assoc()):
    $categoria = htmlspecialchars($row['categoria']);
    $img = $imagenes_disponibles[$i % count($imagenes_disponibles)];
    $i++;
  ?>
    <div class="card" data-aos="fade-up" onclick="location.href='ver_categoria.php?categoria=<?= urlencode($categoria) ?>'">
      <img src="<?= $img ?>" alt="<?= $categoria ?>" data-aos="zoom-in">
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
