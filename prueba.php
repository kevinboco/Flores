<?php
include 'conexion.php';
include 'texto circular.php';

$sql = "SELECT DISTINCT categoria FROM catalogo_ramos";
$result = $conn->query($sql);

// Asocia una imagen a cada categoría
$imagenes = [
  'rosas' => 'https://images.pexels.com/photos/931185/pexels-photo-931185.jpeg',
  'girasoles' => 'https://images.pexels.com/photos/931179/pexels-photo-931179.jpeg',
  'RAMOS BUCHONES' => 'https://images.pexels.com/photos/1903962/pexels-photo-1903962.jpeg',
  'ROSAS EN FORMA DE CORAZON' => 'https://images.pexels.com/photos/356286/pexels-photo-356286.jpeg',
  'RAMOS CON LUCES' => 'https://images.pexels.com/photos/1903962/pexels-photo-1903962.jpeg',
   '60000' => 'https://images.pexels.com/photos/931179/pexels-photo-931179.jpeg',

];
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
    }

    .card:hover {
      transform: translateY(-6px);
    }

    .card img {
      width: 100%;
      height: auto;
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

<h1>Categorías de Ramos</h1>

<div class="container">

  <!-- Opción "Ver todos" -->
  <div class="card" data-aos="fade-up">
    <img src="https://images.pexels.com/photos/931162/pexels-photo-931162.jpeg" alt="Ver Todos">
    <div class="card-content">
      <h3>Ver Todos</h3>
      <button onclick="location.href='ver_categoria.php'">Ver más</button>
    </div>
  </div>

  <?php while($row = $result->fetch_assoc()): 
    $categoria = $row['categoria'];
    $imagen = $imagenes[$categoria] ?? 'https://images.pexels.com/photos/931162/pexels-photo-931162.jpeg'; // Imagen por defecto
  ?>
    <div class="card" data-aos="fade-up">
      <img src="<?= htmlspecialchars($imagen) ?>" alt="<?= htmlspecialchars($categoria) ?>">
      <div class="card-content">
        <h3><?= htmlspecialchars($categoria) ?></h3>
        <button onclick="location.href='ver_categoria.php?categoria=<?= urlencode($categoria) ?>'">Ver más</button>
      </div>
    </div>
  <?php endwhile; ?>

</div>

<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000, once: true });
</script>

</body>
</html>
