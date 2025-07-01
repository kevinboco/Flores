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
  <title>Flora Bella - Catálogo: <?= htmlspecialchars($categoria) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌸</text></svg>">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary: #e91e63;
      --primary-dark: #c2185b;
      --secondary: #f8bbd9;
      --accent: #ff6b9d;
      --gold: #ffd700;
      --background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --surface: rgba(255, 255, 255, 0.95);
      --text: #2d3436;
      --text-light: #636e72;
      --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      --shadow-hover: 0 20px 60px rgba(0, 0, 0, 0.15);
      --border-radius: 20px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--background);
      color: var(--text);
      line-height: 1.6;
      overflow-x: hidden;
    }

    /* Navbar moderna */
    .navbar {
      position: sticky;
      top: 0;
      z-index: 100;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      padding: 1rem 1.5rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .navbar a {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      text-decoration: none;
      color: var(--primary);
      font-weight: 600;
      font-size: 1rem;
      padding: 0.75rem 1.5rem;
      border-radius: 50px;
      background: linear-gradient(135deg, var(--secondary), rgba(233, 30, 99, 0.1));
      transition: var(--transition);
      box-shadow: 0 4px 15px rgba(233, 30, 99, 0.2);
    }

    .navbar a:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(233, 30, 99, 0.3);
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: white;
    }

    /* Título hero */
    .hero-title {
      text-align: center;
      margin: 2rem 1rem;
      font-size: clamp(1.8rem, 5vw, 3rem);
      font-weight: 700;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
    }

    /* Filtros modernos */
    .filters-container {
      max-width: 900px;
      margin: 2rem auto;
      text-align: center;
      padding: 0 1rem;
    }

    .filter-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 1rem 2rem;
      margin: 0.5rem;
      border: none;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: white;
      border-radius: 50px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: var(--transition);
      box-shadow: var(--shadow);
    }

    .filter-btn:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-hover);
    }

    /* Formularios de filtro */
    .filter-form {
      display: none;
      width: 90%;
      max-width: 500px;
      margin: 1.5rem auto;
      padding: 2rem;
      background: var(--surface);
      backdrop-filter: blur(20px);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .filter-form form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .filter-form input {
      padding: 1rem;
      font-size: 1rem;
      border: 2px solid transparent;
      border-radius: 15px;
      background: rgba(255, 255, 255, 0.9);
      transition: var(--transition);
    }

    .filter-form input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.1);
    }

    .filter-form button {
      padding: 1rem 2rem;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: white;
      border: none;
      border-radius: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
    }

    /* Grid de productos */
    .container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      padding: 2rem 1rem;
      max-width: 1400px;
      margin: 0 auto;
    }

    /* Cards modernas */
    .card {
      background: var(--surface);
      backdrop-filter: blur(20px);
      border-radius: var(--border-radius);
      overflow: hidden;
      transition: var(--transition);
      box-shadow: var(--shadow);
      border: 1px solid rgba(255, 255, 255, 0.2);
      position: relative;
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      opacity: 0;
      transition: var(--transition);
    }

    .card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: var(--shadow-hover);
    }

    .card:hover::before {
      opacity: 1;
    }

    .card-media {
      position: relative;
      overflow: hidden;
      aspect-ratio: 4/3;
    }

    .card img, .card video {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: var(--transition);
    }

    .card:hover img, .card:hover video {
      transform: scale(1.1);
    }

    .card-info {
      padding: 1.5rem;
    }

    .card-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--primary);
      margin-bottom: 0.5rem;
    }

    .card-price {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--accent);
      margin-bottom: 0.75rem;
    }

    .card-description {
      color: var(--text-light);
      font-size: 0.9rem;
      margin-bottom: 1.5rem;
      line-height: 1.5;
    }

    /* Botones de acción */
    .action-buttons {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0.75rem;
      margin-top: 1rem;
    }

    .action-buttons .btn {
      padding: 0.75rem 1rem;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      text-align: center;
      transition: var(--transition);
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn-whatsapp {
      background: linear-gradient(135deg, #25D366, #128C7E);
      color: white;
    }

    .btn-customize {
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: white;
    }

    .btn-view {
      background: linear-gradient(135deg, #6c63ff, #5a52ff);
      color: white;
      grid-column: 1 / -1;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    /* MODAL ULTRA INCREÍBLE */
    .modal {
      display: none;
      position: fixed;
      z-index: 99999;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 20% 50%, rgba(255, 107, 157, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(233, 30, 99, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(255, 215, 0, 0.2) 0%, transparent 50%),
        rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(15px);
      justify-content: center;
      align-items: center;
      padding: 1rem;
      animation: modalBackdropFadeIn 0.5s ease;
    }

    .modal-content {
      background: 
        linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
      backdrop-filter: blur(25px);
      border-radius: 30px;
      padding: 0;
      width: 95%;
      max-width: 500px;
      max-height: 90vh;
      overflow: hidden;
      position: relative;
      text-align: center;
      border: 2px solid rgba(255, 255, 255, 0.3);
      box-shadow: 
        0 30px 80px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
      animation: modalContentFadeIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
      transform-origin: center;
    }

    .modal-content.fade-out {
      animation: modalContentFadeOut 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    /* Header del modal con gradiente mágico */
    .modal-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #e91e63 100%);
      background-size: 200% 200%;
      animation: gradientShift 4s ease infinite;
      color: white;
      padding: 2rem;
      position: relative;
      overflow: hidden;
    }

    .modal-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      animation: shimmerEffect 2s infinite;
    }

    .modal-close {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      cursor: pointer;
      transition: var(--transition);
      color: white;
      z-index: 10;
    }

    .modal-close:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: rotate(90deg) scale(1.1);
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
    }

    .modal-title {
      font-size: 1.8rem;
      font-weight: 800;
      margin: 0;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      position: relative;
      z-index: 2;
    }

    .modal-subtitle {
      font-size: 1rem;
      opacity: 0.9;
      margin-top: 0.5rem;
      font-weight: 400;
      position: relative;
      z-index: 2;
    }

    /* Contenido del modal */
    .modal-body {
      padding: 2rem;
      overflow-y: auto;
      max-height: calc(90vh - 120px);
    }

    .modal-image {
      width: 100%;
      max-height: 280px;
      object-fit: cover;
      border-radius: 20px;
      margin-bottom: 2rem;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      transition: var(--transition);
      position: relative;
    }

    .modal-image:hover {
      transform: scale(1.05);
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    /* Selector de colores ULTRA PREMIUM */
    .color-section {
      margin: 2rem 0;
    }

    .color-section-title {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 1.5rem;
      position: relative;
      display: inline-block;
    }

    .color-section-title::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 100%;
      height: 3px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      border-radius: 2px;
    }

    .color-options {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 1rem;
      margin: 1.5rem 0;
    }

    .color-option {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      border: 3px solid white;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      box-shadow: 
        0 8px 25px rgba(0, 0, 0, 0.15),
        0 0 0 0 rgba(233, 30, 99, 0.4);
      position: relative;
      overflow: hidden;
    }

    .color-option::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: var(--transition);
      transform: rotate(-45deg);
      opacity: 0;
    }

    .color-option:hover {
      transform: scale(1.2) translateY(-5px);
      box-shadow: 
        0 15px 40px rgba(0, 0, 0, 0.25),
        0 0 0 8px rgba(233, 30, 99, 0.2);
    }

    .color-option:hover::before {
      opacity: 1;
      animation: colorShine 0.6s ease;
    }

    .color-option.selected {
      transform: scale(1.15);
      box-shadow: 
        0 15px 40px rgba(0, 0, 0, 0.25),
        0 0 0 5px rgba(233, 30, 99, 0.6),
        0 0 0 10px rgba(233, 30, 99, 0.2);
      border-color: var(--gold);
    }

    .color-option::after {
      content: '✓';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) scale(0);
      color: white;
      font-weight: bold;
      font-size: 1.2rem;
      text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
      transition: var(--transition);
    }

    .color-option.selected::after {
      transform: translate(-50%, -50%) scale(1);
      animation: checkmarkBounce 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* Input personalizado PREMIUM */
    .custom-input {
      margin: 2rem 0;
      position: relative;
    }

    .custom-input label {
      display: block;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 1rem;
      font-size: 1.1rem;
    }

    .custom-input input {
      width: 100%;
      padding: 1.2rem 1.5rem;
      border: 2px solid transparent;
      border-radius: 20px;
      font-size: 1rem;
      transition: var(--transition);
      background: 
        linear-gradient(white, white) padding-box,
        linear-gradient(135deg, var(--primary), var(--accent)) border-box;
      outline: none;
      position: relative;
    }

    .custom-input input:focus {
      box-shadow: 
        0 0 0 3px rgba(233, 30, 99, 0.1),
        0 10px 25px rgba(0, 0, 0, 0.1);
      transform: translateY(-2px);
    }

    .custom-input input::placeholder {
      color: var(--text-light);
    }

    /* Cinta personalizada MÁGICA */
    .ribbon-container {
      margin: 2rem 0;
      position: relative;
      display: inline-block;
      transition: var(--transition);
    }

    .ribbon-container:hover {
      transform: scale(1.05);
    }

    .ribbon-image {
      max-width: 100%;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .ribbon-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-weight: 700;
      font-size: 1rem;
      color: #2d3436;
      text-shadow: 
        1px 1px 2px rgba(255, 255, 255, 0.8),
        0 0 10px rgba(255, 255, 255, 0.5);
      max-width: 80%;
      word-wrap: break-word;
      animation: textGlow 2s ease-in-out infinite alternate;
    }

    /* Botón WhatsApp ESPECTACULAR */
    .whatsapp-btn {
      width: 100%;
      margin-top: 2rem;
      padding: 1.2rem 2rem;
      background: linear-gradient(135deg, #25D366, #128C7E, #075E54);
      background-size: 200% 200%;
      color: white;
      border: none;
      border-radius: 20px;
      font-weight: 700;
      font-size: 1.1rem;
      cursor: pointer;
      transition: var(--transition);
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
      box-shadow: 
        0 10px 30px rgba(37, 211, 102, 0.3),
        0 0 0 0 rgba(37, 211, 102, 0.4);
      position: relative;
      overflow: hidden;
      animation: gradientShift 3s ease infinite;
    }

    .whatsapp-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: var(--transition);
    }

    .whatsapp-btn:hover {
      transform: translateY(-3px);
      box-shadow: 
        0 20px 50px rgba(37, 211, 102, 0.4),
        0 0 0 8px rgba(37, 211, 102, 0.1);
      animation: pulse 1s infinite;
    }

    .whatsapp-btn:hover::before {
      left: 100%;
      transition: left 0.6s ease;
    }

    .whatsapp-btn:active {
      transform: translateY(-1px);
    }

    /* Barra inferior */
    .bottom-bar {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: white;
      padding: 1rem;
      text-align: center;
      font-weight: 600;
      z-index: 1000;
      box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(20px);
    }

    /* ANIMACIONES ÉPICAS */
    @keyframes modalBackdropFadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes modalContentFadeIn {
      from {
        opacity: 0;
        transform: scale(0.7) translateY(-50px) rotateX(15deg);
      }
      to {
        opacity: 1;
        transform: scale(1) translateY(0) rotateX(0deg);
      }
    }

    @keyframes modalContentFadeOut {
      from {
        opacity: 1;
        transform: scale(1) translateY(0) rotateX(0deg);
      }
      to {
        opacity: 0;
        transform: scale(0.7) translateY(-50px) rotateX(15deg);
      }
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    @keyframes shimmerEffect {
      0% { left: -100%; }
      100% { left: 100%; }
    }

    @keyframes colorShine {
      0% { transform: rotate(-45deg) translateX(-100%); }
      100% { transform: rotate(-45deg) translateX(100%); }
    }

    @keyframes checkmarkBounce {
      0% { transform: translate(-50%, -50%) scale(0); }
      50% { transform: translate(-50%, -50%) scale(1.3); }
      100% { transform: translate(-50%, -50%) scale(1); }
    }

    @keyframes textGlow {
      from { text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8), 0 0 10px rgba(255, 255, 255, 0.5); }
      to { text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 215, 0, 0.8); }
    }

    @keyframes pulse {
      0% { box-shadow: 0 20px 50px rgba(37, 211, 102, 0.4), 0 0 0 0 rgba(37, 211, 102, 0.1); }
      50% { box-shadow: 0 20px 50px rgba(37, 211, 102, 0.4), 0 0 0 12px rgba(37, 211, 102, 0.05); }
      100% { box-shadow: 0 20px 50px rgba(37, 211, 102, 0.4), 0 0 0 0 rgba(37, 211, 102, 0.1); }
    }

    /* Responsive */
    @media (max-width: 768px) {
      .container {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        padding: 1.5rem 1rem;
      }
      
      .action-buttons {
        grid-template-columns: 1fr;
        gap: 0.5rem;
      }
      
      .btn-view {
        grid-column: 1;
      }
      
      .modal-content {
        width: 98%;
        max-height: 95vh;
      }
      
      .modal-body {
        padding: 1.5rem;
      }
      
      .color-option {
        width: 50px;
        height: 50px;
      }
      
      .bottom-bar {
        padding: 0.75rem;
        font-size: 0.9rem;
      }
    }

    @media (max-width: 480px) {
      .container {
        grid-template-columns: 1fr;
        padding: 1rem;
      }
      
      .filter-form {
        padding: 1.5rem;
      }
      
      .modal-header {
        padding: 1.5rem;
      }
      
      .modal-title {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
  <a href="index.php">← Volver a categorías</a>
</nav>

<!-- Título -->
<h1 class="hero-title" data-aos="fade-down">
  🌸 Ramos: <?= $categoria ? htmlspecialchars($categoria) : 'Todos' ?>
</h1>

<!-- Filtros -->
<div class="filters-container">
  <button class="filter-btn" onclick="toggleFiltro('nombre')" data-aos="zoom-in" data-aos-delay="100">
    🔍 Filtrar por Nombre
  </button>
  <button class="filter-btn" onclick="toggleFiltro('precio')" data-aos="zoom-in" data-aos-delay="200">
    💰 Filtrar por Precio
  </button>
</div>

<?php
$hayFiltrosActivos = (!empty($nombre)) || ($min > 0 || $max > 0);
if ($hayFiltrosActivos):
?>
<div style="text-align: center; margin: 1.5rem;">
  <a href="ver_categoria.php?categoria=<?= urlencode($categoria) ?>" 
     style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #ffcad4, #ffc8dd); color: #721c24; padding: 1rem 2rem; border-radius: 50px; text-decoration: none; font-weight: 600; box-shadow: var(--shadow); transition: var(--transition);">
    ❌ Quitar filtros
  </a>
</div>
<?php endif; ?>

<!-- Formulario filtro nombre -->
<div id="filtro-nombre" class="filter-form" data-aos="fade-up">
  <form method="GET" action="">
    <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
    <input type="text" name="nombre" placeholder="🔍 Escribe el nombre del ramo..." value="<?= htmlspecialchars($nombre) ?>">
    <button type="submit">Buscar</button>
  </form>
</div>

<!-- Formulario filtro precio -->
<div id="filtro-precio" class="filter-form" data-aos="fade-up">
  <form method="GET" action="">
    <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
    <input type="number" name="min" placeholder="💰 Precio mínimo" value="<?= $min ?>">
    <input type="number" name="max" placeholder="💰 Precio máximo" value="<?= $max ?>">
    <button type="submit">Filtrar</button>
  </form>
</div>

<!-- Grid de productos -->
<div class="container">
<?php while($row = $result->fetch_assoc()):
  $titulo = htmlspecialchars($row['titulo']);
  $archivo = './uploads/' . htmlspecialchars($row['imagen']);
  $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
  $es_video = in_array($extension, ['mp4', 'webm', 'ogg', 'mov']);
?>
  <div class="card" data-aos="zoom-in" data-aos-delay="<?= rand(0, 300) ?>">
    <div class="card-media">
      <?php if ($es_video): ?>
        <video autoplay muted loop playsinline>
          <source src="<?= $archivo ?>" type="<?= $extension === 'mov' ? 'video/quicktime' : 'video/' . $extension ?>">
        </video>
      <?php else: ?>
        <img src="<?= $archivo ?>" alt="<?= $titulo ?>" loading="lazy">
      <?php endif; ?>
    </div>
    
    <div class="card-info">
      <h3 class="card-title"><?= $titulo ?></h3>
      <div class="card-price">$<?= number_format($row['valor']) ?></div>
      <p class="card-description"><?= htmlspecialchars($row['description']) ?></p>
      
      <div class="action-buttons">
        <a class="btn btn-whatsapp" href="https://wa.me/573215116044?text=<?= urlencode("¡Hola! Quiero este hermoso ramo: $titulo") ?>" target="_blank">
          💐 Lo quiero
        </a>
        <button class="btn btn-customize" onclick="abrirModal('<?= $titulo ?>')">
          🎨 Personalizar
        </button>
        <a class="btn btn-view" href="ver_producto.php?id=<?= $row['id'] ?>">
          🔍 Ver detalles
        </a>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>

<!-- MODAL ULTRA INCREÍBLE -->
<div class="modal" id="modalPersonalizar" onclick="cerrarModal()">
  <div class="modal-content" onclick="event.stopPropagation()">
    
    <!-- Header mágico del modal -->
    <div class="modal-header">
      <button class="modal-close" onclick="cerrarModal()">×</button>
      <h3 class="modal-title">✨ Personaliza tu Ramo Perfecto ✨</h3>
      <p class="modal-subtitle">Crea algo único y especial</p>
    </div>
    
    <!-- Cuerpo del modal -->
    <div class="modal-body">
      <img id="imagenRamo" src="privado/flor amarilla.jpeg" alt="Ramo" class="modal-image">
      
      <!-- Sección de colores premium -->
      <div class="color-section">
        <div class="color-section-title">🎨 Elige tu color favorito</div>
        <div class="color-options">
          <div class="color-option selected" 
               style="background: linear-gradient(135deg, #ffd700 0%, #ffed4e 50%, #fff176 100%);" 
               onclick="cambiarImagen('privado/flor amarilla.jpeg', 'amarillo', this)"
               data-color="Amarillo Dorado"></div>
          
          <div class="color-option" 
               style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #e9ecef 100%);" 
               onclick="cambiarImagen('privado/flor blanco.jpeg', 'blanco', this)"
               data-color="Blanco Puro"></div>
          
          <div class="color-option" 
               style="background: linear-gradient(135deg, #3498db 0%, #2980b9 50%, #1abc9c 100%);" 
               onclick="cambiarImagen('privado/flor azul.jpeg', 'azul', this)"
               data-color="Azul Celestial"></div>
          
          <div class="color-option" 
               style="background: linear-gradient(135deg, #e91e63 0%, #ad1457 50%, #c2185b 100%);" 
               onclick="cambiarImagen('privado/flor fuccia.jpeg', 'fucsia', this)"
               data-color="Rosa Fucsia"></div>
          
          <div class="color-option" 
               style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 50%, #922b21 100%);" 
               onclick="cambiarImagen('privado/flor roja.jpeg', 'rojo', this)"
               data-color="Rojo Pasión"></div>
        </div>
      </div>
      
      <!-- Input de texto personalizado -->
      <div class="custom-input">
        <label for="textoCinta">💌 Mensaje personalizado en la cinta</label>
        <input type="text" 
               id="textoCinta" 
               placeholder="Escribe tu mensaje de amor... (+$10.000)" 
               oninput="actualizarTextoCinta()"
               maxlength="50">
      </div>
      
      <!-- Cinta personalizada -->
      <div class="ribbon-container">
        <img src="privado/cinta_personalizada.png" id="imagenCinta" alt="Cinta personalizada" class="ribbon-image">
        <div id="textoSobreCinta" class="ribbon-text"></div>
      </div>
      
      <!-- Botón WhatsApp espectacular -->
      <a id="botonWhatsappModal" class="whatsapp-btn" target="_blank">
        <span style="font-size: 1.5rem;">💐</span>
        ¡Quiero este ramo personalizado!
        <span style="font-size: 1.5rem;">✨</span>
      </a>
    </div>
  </div>
</div>

<!-- Barra inferior -->
<div id="barraWhatsapp" class="bottom-bar">
  🌸 ¿Te enamoraste de algún ramo? ¡Haz clic en "Lo quiero" y te ayudaremos por WhatsApp! ✨
</div>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
// Inicializar AOS
AOS.init({ 
  duration: 800, 
  once: true,
  offset: 100
});

// Variables globales
let tituloActual = "";
let colorSeleccionado = "amarillo";
let textoCinta = "";

// Funciones de filtros
function toggleFiltro(tipo) {
  const nombreDiv = document.getElementById("filtro-nombre");
  const precioDiv = document.getElementById("filtro-precio");

  if (tipo === "nombre") {
    nombreDiv.style.display = nombreDiv.style.display === "none" ? "block" : "none";
    precioDiv.style.display = "none";
  } else if (tipo === "precio") {
    precioDiv.style.display = precioDiv.style.display === "none" ? "block" : "none";
    nombreDiv.style.display = "none";
  }
}

// Funciones del modal
function abrirModal(titulo) {
  tituloActual = titulo;
  colorSeleccionado = "amarillo";
  textoCinta = "";
  
  document.getElementById("imagenRamo").src = "privado/flor amarilla.jpeg";
  document.getElementById("modalPersonalizar").style.display = "flex";
  document.body.style.overflow = "hidden";
  document.getElementById("textoCinta").value = "";
  document.getElementById("textoSobreCinta").innerText = "";
  document.getElementById("barraWhatsapp").style.display = "none";
  
  // Resetear selección de colores
  document.querySelectorAll('.color-option').forEach(option => {
    option.classList.remove('selected');
  });
  document.querySelector('.color-option').classList.add('selected');
  
  actualizarEnlaceWhatsapp();
  
  // Efecto de entrada épico
  setTimeout(() => {
    document.querySelector('.modal-content').style.transform = 'scale(1) translateY(0) rotateX(0deg)';
  }, 50);
}

function cerrarModal() {
  const modal = document.getElementById('modalPersonalizar');
  const contenido = modal.querySelector('.modal-content');

  contenido.classList.add('fade-out');

  setTimeout(() => {
    modal.style.display = 'none';
    contenido.classList.remove('fade-out');
    document.body.style.overflow = '';
    document.getElementById("barraWhatsapp").style.display = "block";
  }, 400);
}

function cambiarImagen(ruta, color, elemento) {
  // Efecto de transición suave en la imagen
  const imagen = document.getElementById('imagenRamo');
  imagen.style.opacity = '0.5';
  imagen.style.transform = 'scale(0.95)';
  
  setTimeout(() => {
    imagen.src = ruta;
    imagen.style.opacity = '1';
    imagen.style.transform = 'scale(1)';
  }, 200);
  
  colorSeleccionado = color;
  
  // Actualizar selección visual con animación
  document.querySelectorAll('.color-option').forEach(option => {
    option.classList.remove('selected');
  });
  elemento.classList.add('selected');
  
  // Efecto de vibración sutil
  elemento.style.animation = 'checkmarkBounce 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
  setTimeout(() => {
    elemento.style.animation = '';
  }, 500);
  
  actualizarEnlaceWhatsapp();
}

function actualizarTextoCinta() {
  textoCinta = document.getElementById("textoCinta").value;
  const textoElement = document.getElementById("textoSobreCinta");
  
  // Animación de escritura
  textoElement.style.opacity = '0';
  setTimeout(() => {
    textoElement.innerText = textoCinta;
    textoElement.style.opacity = '1';
  }, 150);
  
  actualizarEnlaceWhatsapp();
}

function actualizarEnlaceWhatsapp() {
  let mensaje = `🌸 ¡Hola! Quiero este hermoso ramo: ${tituloActual} en color ${colorSeleccionado}`;
  
  if (textoCinta) {
    mensaje += ` con el mensaje personalizado: "${textoCinta}" (costo adicional de $10.000)`;
  }
  
  mensaje += ". ¿Podrían ayudarme con todos los detalles? ¡Muchas gracias! ✨💐";
  
  const enlace = `https://wa.me/573215116044?text=${encodeURIComponent(mensaje)}`;
  document.getElementById('botonWhatsappModal').href = enlace;
}

// Cerrar modal con tecla Escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    cerrarModal();
  }
});

// Efectos adicionales y mejoras de UX
document.addEventListener('DOMContentLoaded', function() {
  // Precargar imágenes del modal para transiciones suaves
  const imagenesModal = [
    'privado/flor amarilla.jpeg',
    'privado/flor blanco.jpeg', 
    'privado/flor azul.jpeg',
    'privado/flor fuccia.jpeg',
    'privado/flor roja.jpeg'
  ];
  
  imagenesModal.forEach(src => {
    const img = new Image();
    img.src = src;
  });
  
  // Efecto de hover en las opciones de color
  document.querySelectorAll('.color-option').forEach(option => {
    option.addEventListener('mouseenter', function() {
      this.style.transform = 'scale(1.2) translateY(-5px)';
    });
    
    option.addEventListener('mouseleave', function() {
      if (!this.classList.contains('selected')) {
        this.style.transform = 'scale(1) translateY(0)';
      }
    });
  });
  
  // Efecto de paralaje sutil en el modal
  document.addEventListener('mousemove', function(e) {
    const modal = document.querySelector('.modal-content');
    if (modal && modal.offsetParent !== null) {
      const rect = modal.getBoundingClientRect();
      const centerX = rect.left + rect.width / 2;
      const centerY = rect.top + rect.height / 2;
      const deltaX = (e.clientX - centerX) / 50;
      const deltaY = (e.clientY - centerY) / 50;
      
      modal.style.transform = `perspective(1000px) rotateY(${deltaX}deg) rotateX(${-deltaY}deg)`;
    }
  });
});

// Función para crear partículas flotantes (opcional)
function createFloatingParticles() {
  const modal = document.querySelector('.modal-header');
  if (!modal) return;
  
  for (let i = 0; i < 5; i++) {
    const particle = document.createElement('div');
    particle.style.cssText = `
      position: absolute;
      width: 4px;
      height: 4px;
      background: white;
      border-radius: 50%;
      opacity: 0.7;
      animation: float ${3 + Math.random() * 2}s ease-in-out infinite;
      left: ${Math.random() * 100}%;
      top: ${Math.random() * 100}%;
      animation-delay: ${Math.random() * 2}s;
    `;
    modal.appendChild(particle);
  }
}

// Estilos adicionales para las partículas
const style = document.createElement('style');
style.textContent = `
  @keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.7; }
    50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
  }
`;
document.head.appendChild(style);
</script>

</body>
</html>

