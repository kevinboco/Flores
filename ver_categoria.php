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
    .botones-ramos {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 8px;
      margin-top: 10px;
    }
    .botones-ramos a,
    .botones-ramos button {
      flex: 1 1 30%;
      padding: 10px 8px;
      border-radius: 8px;
      font-weight: bold;
      font-size: 15px;
      cursor: pointer;
      text-decoration: none;
      white-space: nowrap;
      box-sizing: border-box;
    }
    .boton-whatsapp { background: #25D366; color: white; }
    .boton-personalizar { background-color: #e91e63; color: white; border: none; }
    .ver-grande { background: #6c63ff; color: white; }

    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      padding: 10px;
    }
    .modal-contenido {
      background: white;
      border-radius: 10px;
      padding: 15px;
      width: 90%;
      max-width: 300px;
      position: relative;
      text-align: center;
    }
    .cerrar {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 22px;
      font-weight: bold;
      cursor: pointer;
    }
    .modal-contenido img {
      max-width: 100%;
      max-height: 60vh;
      border-radius: 10px;
    }
    .color-opciones {
      margin-top: 15px;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 10px;
    }
    .color-opcion {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 2px solid #555;
      cursor: pointer;
    }
    .barra-fija-inferior.whatsapp {
      background: linear-gradient(to right, #e0f2fe, #d8b4fe);
      color: #1e3a8a;
      font-size: 0.95rem;
      padding: 10px;
      box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.08);
    }

  </style>
</head>
<body>
<div class="navbar">
  <a href="index.php">Volver a categorías</a>
</div>
<h1>Ramos: <?= $categoria ? htmlspecialchars($categoria) : 'Todos' ?></h1>
<div class="container">
<?php while($row = $result->fetch_assoc()):
  $titulo = htmlspecialchars($row['titulo']);
  $archivo = './uploads/' . htmlspecialchars($row['imagen']);
  $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
  $es_video = in_array($extension, ['mp4', 'webm', 'ogg', 'mov']);
?>
  <div class="card" data-aos="zoom-in">
    <?php if ($es_video): ?>
      <video autoplay muted loop playsinline controls>
      <source src="<?= $archivo ?>" type="<?= $extension === 'mov' ? 'video/quicktime' : 'video/' . $extension ?>">
      Tu navegador no soporta la reproducción de este video.
    </video>

    <?php else: ?>
      <img src="<?= $archivo ?>" alt="<?= $titulo ?>">
    <?php endif; ?>
    <div class="info">
      <h3 data-aos="fade-right"><?= $titulo ?></h3>
      <p data-aos="fade-left"><strong>Precio:</strong> $<?= number_format($row['valor']) ?></p>
      <p data-aos="fade-up"><?= htmlspecialchars($row['description']) ?></p>
      <div class="botones-ramos">
        <a class="boton-whatsapp" href="https://wa.me/573215116044?text=<?= urlencode("Quiero este producto: $titulo") ?>" target="_blank">💐 Lo quiero</a>
        <button class="boton-personalizar" onclick="abrirModal('<?= $titulo ?>')">🎨 Personalizar</button>
        <a class="boton-whatsapp ver-grande" href="ver_producto.php?id=<?= $row['id'] ?>">🔍 Ver en grande</a>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>

<div class="modal" id="modalPersonalizar" onclick="cerrarModal()">
  <div class="modal-contenido" onclick="event.stopPropagation()">
    <span class="cerrar" onclick="cerrarModal()">&times;</span>
    <h3>Elige el color del ramo</h3>
    <img id="imagenRamo" src="privado/flor amarillo.jpeg" alt="Ramo">
    <div class="color-opciones">
      <div class="color-opcion" style="background:yellow;" onclick="cambiarImagen('privado/flor amarilla.jpeg')"></div>
      <div class="color-opcion" style="background:white;" onclick="cambiarImagen('privado/flor blanco.jpeg')"></div>
      <div class="color-opcion" style="background:blue;" onclick="cambiarImagen('privado/flor azul.jpeg')"></div>
      <div class="color-opcion" style="background:pink;" onclick="cambiarImagen('privado/flor fuccia.jpeg')"></div>
      <div class="color-opcion" style="background:red;" onclick="cambiarImagen('privado/flor roja.jpeg')"></div>
    </div>
    <a id="botonWhatsappModal" class="boton-whatsapp" target="_blank">💐 Lo quiero con este color</a>
  </div>
</div>

<script>
let tituloActual = "";
let colorSeleccionado = "amarillo";

function abrirModal(titulo) {
  tituloActual = titulo;
  colorSeleccionado = "amarillo";
  document.getElementById('imagenRamo').src = 'privado/flor amarilla.jpeg';
  document.getElementById('modalPersonalizar').style.display = 'flex';
  document.body.style.overflow = 'hidden';
  actualizarEnlaceWhatsapp();
}

function cerrarModal() {
  document.getElementById('modalPersonalizar').style.display = 'none';
  document.body.style.overflow = '';
}

function cambiarImagen(ruta) {
  document.getElementById('imagenRamo').src = ruta;
  if (ruta.includes("amarillo")) colorSeleccionado = "amarillo";
  else if (ruta.includes("blanco")) colorSeleccionado = "blanco";
  actualizarEnlaceWhatsapp();
}

function actualizarEnlaceWhatsapp() {
  const mensaje = `Quiero este ramo: ${tituloActual} en color ${colorSeleccionado}`;
  const enlace = `https://wa.me/573215116044?text=${encodeURIComponent(mensaje)}`;
  document.getElementById('botonWhatsappModal').href = enlace;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({ duration: 800, once: true });</script>
<div class="barra-fija-inferior whatsapp">
  📲 ¿Te gustó algún ramo? Haz clic en lo !quiero! y te llevaremos a WhatsApp con el nombre del producto listo para consultar ✨
</div>

</body>
</html>
