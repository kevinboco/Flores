<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Categorías de Ramos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- AOS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background-color: #f9f9fb;
      color: #333;
    }

    h1 {
      text-align: center;
      margin: 40px 20px 20px;
      color: #d63384;
    }

    .container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
      padding: 40px;
      max-width: 1200px;
      margin: auto;
    }

    .card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 20px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-6px);
    }

    .card img {
      width: 100%;
      height: 180px;
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
  <div class="card" data-aos="fade-up">
    <img src="https://images.unsplash.com/photo-1504203700686-0f4b3c655b9a?auto=format&fit=crop&w=800&q=80" alt="Rosas">
    <div class="card-content">
      <h3>Rosas románticas</h3>
      <button>Ver más</button>
    </div>
  </div>

  <div class="card" data-aos="fade-up">
    <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80" alt="Girasoles">
    <div class="card-content">
      <h3>Girasoles brillantes</h3>
      <button>Ver más</button>
    </div>
  </div>

  <div class="card" data-aos="fade-up">
    <img src="https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=800&q=80" alt="Tulipanes">
    <div class="card-content">
      <h3>Tulipanes elegantes</h3>
      <button>Ver más</button>
    </div>
  </div>

  <div class="card" data-aos="fade-up">
    <img src="https://images.unsplash.com/photo-1495150132531-c4f7b3c2cd40?auto=format&fit=crop&w=800&q=80" alt="Bouquet">
    <div class="card-content">
      <h3>Ramos mixtos</h3>
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

