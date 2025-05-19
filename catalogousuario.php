<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carrusel Arrastrable</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #111;
      font-family: sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .stack-container {
      display: flex;
      gap: 20px;
      padding: 20px;
      overflow-x: auto;
      scroll-behavior: smooth;
      cursor: grab;
      user-select: none;
      max-width: 90vw;
    }

    .card {
      flex: 0 0 auto;
      width: 300px;
      height: 400px;
      border-radius: 20px;
      overflow: hidden;
      border: 5px solid white;
      box-shadow: 0 8px 15px rgba(0,0,0,0.5);
      background: #222;
    }

    .card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      pointer-events: none;
    }
  </style>
</head>
<body>

<button class="back-button" onclick="location.href='index.php'">Volver a Inicio</button>
<div class="stack-container" id="carousel">
  <?php
    $folder = "uploads/";
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $images = array_filter(scandir($folder), function($file) use ($folder, $allowed) {
      $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
      return in_array($ext, $allowed) && is_file($folder . $file);
    });

    foreach ($images as $img) {
      echo "
        <div class='card'>
          <img src='$folder$img' alt='Imagen'>
        </div>
      ";
    }
  ?>
</div>

<script>
  const carousel = document.getElementById('carousel');
  let isDown = false;
  let startX;
  let scrollLeft;

  carousel.addEventListener('mousedown', (e) => {
    isDown = true;
    carousel.classList.add('active');
    startX = e.pageX - carousel.offsetLeft;
    scrollLeft = carousel.scrollLeft;
  });

  carousel.addEventListener('mouseleave', () => {
    isDown = false;
  });

  carousel.addEventListener('mouseup', () => {
    isDown = false;
  });

  carousel.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - carousel.offsetLeft;
    const walk = (x - startX) * 2; // Velocidad del scroll
    carousel.scrollLeft = scrollLeft - walk;
  });
</script>

</body>
</html>
