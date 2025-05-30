<?php
include 'conexion.php';

$categoria = $_GET['categoria'] ?? '';
$min = isset($_GET['min']) ? (int)$_GET['min'] : 0;
$max = isset($_GET['max']) ? (int)$_GET['max'] : 0;

$params = [];
$types = '';
$sql = "SELECT * FROM catalogo_ramos WHERE 1";

// Filtro por categoría
if (!empty($categoria)) {
    $sql .= " AND categoria = ?";
    $types .= 's';
    $params[] = $categoria;
}

// Filtro por precio
if ($min > 0 && $max > 0 && $min <= $max) {
    $sql .= " AND valor BETWEEN ? AND ?";
    $types .= 'ii';
    $params[] = $min;
    $params[] = $max;
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

  <h1>Ramos: <?= $categoria ? htmlspecialchars($categoria) : 'Todos' ?></h1>
  <div style="text-align:center; margin-bottom: 20px;">
    <button onclick="toggleFiltro()" style="padding: 10px 20px; background: #d63384; color: white; border: none; border-radius: 8px; font-size: 16px;">
      Filtrar por precio
    </button>

    <div id="filtroPrecio" style="display: none; margin-top: 20px;">
      <form method="GET">
        <?php if (isset($_GET['categoria'])): ?>
          <input type="hidden" name="categoria" value="<?= htmlspecialchars($_GET['categoria']) ?>">
        <?php endif; ?>

        <label>Precio mínimo: <span id="minValor"><?= isset($_GET['min']) ? $_GET['min'] : 0 ?></span></label><br>
        <input type="range" name="min" id="min" min="0" max="100000" step="1000" value="<?= isset($_GET['min']) ? $_GET['min'] : 0 ?>" oninput="minValor.textContent = this.value"><br><br>

        <label>Precio máximo: <span id="maxValor"><?= isset($_GET['max']) ? $_GET['max'] : 100000 ?></span></label><br>
        <input type="range" name="max" id="max" min="0" max="100000" step="1000" value="<?= isset($_GET['max']) ? $_GET['max'] : 100000 ?>" oninput="maxValor.textContent = this.value"><br><br>

        <button type="submit" style="padding: 10px 20px; background: #d63384; color: white; border: none; border-radius: 8px;">Aplicar filtro</button>
      </form>
    </div>
  </div>

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

            <button onclick="togglePersonalizar(this)" style="margin-top:10px; padding: 8px 14px; border-radius: 6px; border:none; background:#ffc107; font-weight:bold;">✨ Personalizar</button>

            <div class="personalizacion" style="display:none; margin-top: 10px; font-size:14px;">
              <label>Color de rosas:
                <select>
                  <option value="rojas">Rojas</option>
                  <option value="blancas">Blancas</option>
                  <option value="amarillas">Amarillas</option>
                  <option value="rosadas">Rosadas</option>
                </select>
              </label><br><br>

              <label><input type="checkbox" value="Luces (+$10.000)"> Luces (+$10.000)</label><br>
              <label><input type="checkbox" value="Oso (+$30.000)"> Oso (+$30.000)</label><br>
              <label><input type="checkbox" value="Corona (+$5.000)"> Corona (+$5.000)</label><br>
              <label><input type="checkbox" value="Gorra (+$20.000)"> Gorra (+$20.000)</label><br><br>

              <label>Cinta personalizada: <input type="text" placeholder="Escribe un mensaje..."></label><br><br>

              <label>Tarjeta:
                <select>
                  <option value="">(Sin tarjeta)</option>
                  <option value="Feliz Cumpleaños">Feliz Cumpleaños</option>
                  <option value="Te Amo">Te Amo</option>
                  <option value="Gracias">Gracias</option>
                  <option value="Recupérate Pronto">Recupérate Pronto</option>
                </select>
              </label><br><br>

              <button onclick="enviarWhatsApp(this, '<?= $titulo ?>')" style="padding: 8px 14px; border-radius: 6px; border:none; background:#25D366; color:white; font-weight:bold;">💐 Lo quiero personalizado</button>
            </div>
          </div>

          <a class="boton-whatsapp" href="<?= $link ?>" target="_blank">💐 Lo quiero</a>
          <a class="boton-whatsapp" style="background:#6c63ff; margin-top: -10px;" href="ver_producto.php?id=<?= $row['id'] ?>">🔍 Ver en grande</a>
        </div>
      <?php endwhile; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>AOS.init({ duration: 800, once: true });</script>
  <script>
    function toggleFiltro() {
      const filtro = document.getElementById('filtroPrecio');
      filtro.style.display = (filtro.style.display === 'none') ? 'block' : 'none';
    }
  </script>
  <script>
      function togglePersonalizar(btn) {
        const section = btn.nextElementSibling;
        section.style.display = (section.style.display === 'none') ? 'block' : 'none';
      }

      function enviarWhatsApp(btn, titulo) {
        const contenedor = btn.parentElement;
        const color = contenedor.querySelector('select').value;
        const checks = contenedor.querySelectorAll('input[type="checkbox"]:checked');
        const cinta = contenedor.querySelector('input[type="text"]').value;
        const tarjeta = contenedor.querySelectorAll('select')[1].value;

        let extras = [];
        if (color) extras.push(`Color: ${color}`);
        checks.forEach(c => extras.push(c.value));
        if (cinta) extras.push(`Cinta: "${cinta}"`);
        if (tarjeta) extras.push(`Tarjeta: ${tarjeta}`);

        const mensaje = `Quiero este producto: ${titulo}%0AExtras: ${extras.join('%0A')}`;
        const url = `https://wa.me/573215116044?text=${mensaje}`;
        window.open(url, '_blank');
      }
    </script>

</body>
</html>