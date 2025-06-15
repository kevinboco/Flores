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

  <!-- Tarjeta 1 -->
  <div class="card" data-aos="fade-up">
    <img src="https://images.pexels.com/photos/931162/pexels-photo-931162.jpeg" alt="Rosas románticas">
    <div class="card-content">
      <h3>Rosas románticas</h3>
      <button>Ver más</button>
    </div>
  </div>

  <!-- Tarjeta 2 -->
  <div class="card" data-aos="fade-up">
    <img src="https://images.pexels.com/photos/1903962/pexels-photo-1903962.jpeg" alt="Girasoles brillantes">
    <div class="card-content">
      <h3>Girasoles brillantes</h3>
      <button>Ver más</button>
    </div>
  </div>

  <!-- Tarjeta 3 -->
  <div class="card" data-aos="fade-up">
    <img src="https://images.pexels.com/photos/617278/pexels-photo-617278.jpeg" alt="Tulipanes elegantes">
    <div class="card-content">
      <h3>Tulipanes elegantes</h3>
      <button>Ver más</button>
    </div>
  </div>

  <!-- Tarjeta 4 -->
  <div class="card" data-aos="fade-up">
    <img src="https://images.pexels.com/photos/356286/pexels-photo-356286.jpeg" alt="Bouquet mixto">
    <div class="card-content">
      <h3>Ramos mixtos</h3>
      <button>Ver más</button>
    </div>
  </div>

  <!-- Tarjeta 5 -->
  <div class="card" data-aos="fade-up">
    <img src="https://images.pexels.com/photos/931179/pexels-photo-931179.jpeg" alt="Rosas blancas">
    <div class="card-content">
      <h3>Rosas blancas</h3>
      <button>Ver más</button>
    </div>
  </div>

  <!-- Tarjeta 6 -->
  <div class="card" data-aos="fade-up">
    <img src="https://images.pexels.com/photos/618833/pexels-photo-618833.jpeg" alt="Ramos de cumpleaños">
    <div class="card-content">
      <h3>Ramos de cumpleaños</h3>
      <button>Ver más</button>
    </div>
  </div>

  <!-- Tarjeta 7 -->
  <div class="card" data-aos="fade-up">
    <img src="https://images.pexels.com/photos/931185/pexels-photo-931185.jpeg" alt="Ramos primaverales">
    <div class="card-content">
      <h3>Ramos primaverales</h3>
      <button>Ver más</button>
    </div>
  </div>

  <!-- Tarjeta 8 -->
  <div class="card" data-aos="fade-up">
    <img src="https://images.pexels.com/photos/931176/pexels-photo-931176.jpeg" alt="Ramos para aniversario">
    <div class="card-content">
      <h3>Para aniversario</h3>
      <button>Ver más</button>
    </div>
  </div>

  <!-- Tarjeta 9 -->
  <div class="card" data-aos="fade-up">
    <img src="https://images.pexels.com/photos/6485767/pexels-photo-6485767.jpeg" alt="Ramos delicados">
    <div class="card-content">
      <h3>Ramos delicados</h3>
      <button>Ver más</button>
    </div>
  </div>

  <!-- Tarjeta 10 -->
  <div class="card" data-aos="fade-up">
    <img src="https://images.pexels.com/photos/4033089/pexels-photo-4033089.jpeg" alt="Ramos modernos">
    <div class="card-content">
      <h3>Ramos modernos</h3>
      <button>Ver más</button>
    </div>
  </div>

</div>

<!-- AOS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000, once: true });
</script>

</body>
</html>
