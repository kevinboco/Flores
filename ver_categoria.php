<?php
include 'conexion.php';

$categoria = $_GET['categoria'] ?? '';
$min = isset($_GET['min']) ? (int)$_GET['min'] : 0;
$max = isset($_GET['max']) ? (int)$_GET['max'] : 0;
$nombre = $_GET['nombre'] ?? '';

$params = [];
$types = '';
$sql = "SELECT * FROM catalogo_ramos WHERE 1";

if (!empty($categoria)) {
    $sql .= " AND categoria = ?";
    $types .= 's';
    $params[] = $categoria;
}

if ($min > 0 && $max > 0 && $min <= $max) {
    $sql .= " AND valor BETWEEN ? AND ?";
    $types .= 'ii';
    $params[] = $min;
    $params[] = $max;
}

if (!empty($nombre)) {
    $sql .= " AND titulo LIKE ?";
    $types .= 's';
    $params[] = '%' . $nombre . '%';
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Catálogo: <?= htmlspecialchars($categoria) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: linear-gradient(to right, #fff0f6, #ffe5ec);
      color: #333;
    }
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
    }
    .navbar a:hover {
      color: #a61e65;
    }
    .navbar a::before {
      content: "\2190 ";
      margin-right: 5px;
    }
    h1 {
      text-align: center;
      margin: 30px 10px;
      color: #d63384;
      font-size: 30px;
    }

    .toggle-btn {
      padding: 12px 20px;
      background: #d63384;
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      font-size: 15px;
      cursor: pointer;
      transition: background 0.3s ease;
      margin: 10px;
    }

    .toggle-btn:hover {
      background: #a61e65;
    }

    .filtro-form {
      max-width: 1100px;
      margin: 10px auto;
      padding: 25px;
      border-radius: 16px;
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }


    .filtro-grupo {
      display: flex;
      flex-direction: column;
      min-width: 200px;
    }

    .filtro-grupo label {
      font-weight: 600;
      margin-bottom: 6px;
      color: #d63384;
    }

    .filtro-grupo input {
      padding: 10px 14px;
      border: 1px solid #ddd;
      border-radius: 10px;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
    }

    .filtro-grupo button {
      padding: 12px 20px;
      background: #d63384;
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
      font-size: 15px;
      transition: background 0.3s ease;
    }

    .filtro-grupo button:hover {
      background: #a61e65;
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
      display: flex;
      flex-direction: column;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 28px rgba(0,0,0,0.12);
    }

    .card img, .card video {
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
      margin: 15px 0 10px;
      text-align: center;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.3s, transform 0.3s;
      animation: pulse 2s infinite;
      display: inline-block;
    }

    .boton-whatsapp:hover {
      background: #1ebe5c;
      transform: scale(1.05);
    }

    @keyframes pulse {
      0%, 100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.5); }
      50% { box-shadow: 0 0 0 10px rgba(37, 211, 102, 0); }
    }
  </style>
</head>
<body>
<div class="navbar">
  <a href="index.php">Volver a categorías</a>
</div>

<h1>Ramos: <?= $categoria ? htmlspecialchars($categoria) : 'Todos' ?></h1>

<!-- Botones -->
<div class="filtro-form">
  <button type="button" class="toggle-btn" onclick="toggleFiltro('precio')">💰 Filtrar por precio</button>
  <button type="button" class="toggle-btn" onclick="toggleFiltro('nombre')">🔍 Filtrar por nombre</button>
</div>

<!-- Formulario precio -->
<form method="GET" class="filtro-form" id="form-precio" style="display: <?= ($min > 0 || $max > 0) ? 'flex' : 'none' ?>;">
  <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
  <?php if (!empty($nombre)) : ?>
    <input type="hidden" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
  <?php endif; ?>
  <div class="filtro-grupo">
    <label for="min">💰 Precio mínimo</label>
    <input type="number" name="min" id="min" value="<?= $min ?>" placeholder="Ej: 20000">
  </div>
  <div class="filtro-grupo">
    <label for="max">💰 Precio máximo</label>
    <input type="number" name="max" id="max" value="<?= $max ?>" placeholder="Ej: 100000">
  </div>
  <div class="filtro-grupo">
    <button type="submit">Aplicar filtros</button>
  </div>
</form>

<!-- Formulario nombre -->
<form method="GET" class="filtro-form" id="form-nombre" style="display: <?= (!empty($nombre)) ? 'flex' : 'none' ?>;">
  <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
  <?php if ($min > 0) : ?>
    <input type="hidden" name="min" value="<?= $min ?>">
  <?php endif; ?>
  <?php if ($max > 0) : ?>
    <input type="hidden" name="max" value="<?= $max ?>">
  <?php endif; ?>
  <div class="filtro-grupo">
    <label for="nombre">🔍 Buscar por nombre</label>
    <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($nombre) ?>" placeholder="Ej: Rosas con mariposa">
  </div>
  <div class="filtro-grupo">
    <button type="submit">Buscar</button>
  </div>
</form>

<!-- Catálogo -->
<div class="container">
<?php while($row = $result->fetch_assoc()):
  $titulo = htmlspecialchars($row['titulo']);
  $archivo = './uploads/' . htmlspecialchars($row['imagen']);
  $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
  $es_video = in_array($extension, ['mp4', 'webm', 'ogg']);
  $mensaje = urlencode("Quiero este producto: $titulo");
  $link = "https://wa.me/573215116044?text=$mensaje";
?>
  <div class="card" data-aos="fade-up">
    <?php if ($es_video): ?>
      <video autoplay muted loop playsinline>
        <source src="<?= $archivo ?>" type="video/<?= $extension ?>">
      </video>
    <?php else: ?>
      <img src="<?= $archivo ?>" alt="<?= $titulo ?>">
    <?php endif; ?>
    <div class="info">
      <h3 data-aos="fade-right" data-aos-delay="100"><?= $titulo ?></h3>
      <p data-aos="fade-right" data-aos-delay="200"><strong>Precio:</strong> $<?= number_format($row['valor']) ?></p>
      <p data-aos="fade-right" data-aos-delay="300"><?= htmlspecialchars($row['description']) ?></p>
      <a class="boton-whatsapp" href="<?= $link ?>" target="_blank" data-aos="zoom-in" data-aos-delay="400">💐 Lo quiero</a>
      <a class="boton-whatsapp" style="background:#6c63ff" href="ver_producto.php?id=<?= $row['id'] ?>" data-aos="zoom-in" data-aos-delay="500">🔍 Ver en grande</a>
      <?php
       include 'modal.php'; 
      
      ?>
    </div>
  </div>
<?php endwhile; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 800, once: true });

  function toggleFiltro(tipo) {
    const formPrecio = document.getElementById('form-precio');
    const formNombre = document.getElementById('form-nombre');

    if (tipo === 'precio') {
      formPrecio.style.display = (formPrecio.style.display === 'none' || !formPrecio.style.display) ? 'flex' : 'none';
    }

    if (tipo === 'nombre') {
      formNombre.style.display = (formNombre.style.display === 'none' || !formNombre.style.display) ? 'flex' : 'none';
    }
  }
</script>
</body>
</html>
