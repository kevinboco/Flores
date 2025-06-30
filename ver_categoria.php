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
      align-items: flex-start;
      padding: 10px;
      overflow-y: auto; /* ✅ esto permite desplazarse si se pasa de altura */
    }

    .modal-contenido {
      background: pink;
      border-radius: 10px;
      padding: 15px;
      width: 90%;
      max-width: 400px;
      margin: 20px auto;
      box-sizing: border-box;
      max-height: none;
      overflow: visible;
      position: relative;
      text-align: center;

      animation: fadeInModal 0.4s ease; /* 👈 Aquí se agrega */
      
    }
    .modal-contenido.fade-out {
      animation: fadeOutModal 0.3s ease forwards;
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
      max-width: 50%;
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
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background: linear-gradient(to right, #e0f2fe, #d8b4fe);
      color: #1e3a8a;
      font-size: 0.95rem;
      padding: 12px;
      text-align: center;
      font-weight: bold;
      z-index: 9999;
      font-family: 'Poppins', sans-serif;
      box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
    }
    @keyframes fadeInModal {
      from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
      }
      to {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }
    @keyframes fadeOutModal {
      from {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
      to {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
      }
    }
    


  </style>
</head>
<body>
<div class="navbar">
  <a href="index.php">Volver a categorías</a>
</div>
<h1 data-aos="fade-down">Ramos: <?= $categoria ? htmlspecialchars($categoria) : 'Todos' ?></h1>

<!-- Botones de filtros -->
<div style="max-width: 900px; margin: 20px auto; text-align: center;">
  <button onclick="toggleFiltro('nombre')" data-aos="zoom-in" data-aos-delay="100" style="padding: 10px 20px; margin: 10px; border: none; background-color: #d63384; color: white; border-radius: 10px; font-weight: bold; cursor: pointer;">
    🔍 Filtrar por Nombre
  </button>
  <button onclick="toggleFiltro('precio')" data-aos="zoom-in" data-aos-delay="100" style="padding: 10px 20px; margin: 10px; border: none; background-color: #6c63ff; color: white; border-radius: 10px; font-weight: bold; cursor: pointer;">
    💰 Filtrar por Precio
  </button>
</div>
<?php
// Verificamos si hay filtros ACTIVOS diferentes a la categoría actual
$hayFiltrosActivos = false;

if (
    (!empty($nombre)) ||
    ($min > 0 || $max > 0)
) {
    $hayFiltrosActivos = true;
}

if ($hayFiltrosActivos):
?>
  <div style="text-align: center; margin: 20px;">
    <a href="ver_categoria.php?categoria=<?= urlencode($categoria) ?>" style="background: #ffcad4; color: #721c24; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
      ❌ Quitar filtros y ver todos los ramos
    </a>
  </div>
<?php endif; ?>



<!-- Formulario de filtro por nombre -->
<div id="filtro-nombre"  style="display:none; width: 90%; max-width: 500px; margin: 0 auto 20px; padding: 20px; background: #fff; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); box-sizing: border-box;" data-aos="fade-up" data-aos-delay="100">
  <form method="GET" action="" style="display: flex; flex-direction: column; gap: 12px;">
    <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
    <input type="text" name="nombre" placeholder="Escribe el nombre..." value="<?= htmlspecialchars($nombre) ?>" style="padding: 12px; font-size: 16px; border: 2px solid #d63384; border-radius: 10px; box-sizing: border-box; width: 100%;">
    <button type="submit" style="padding: 10px 20px; background-color: #d63384; color: white; border: none; border-radius: 10px; font-weight: bold; cursor: pointer;">Filtrar</button>
  </form>
</div>

<!-- Formulario de filtro por precio -->
<div id="filtro-precio" style="display:none; width: 90%; max-width: 500px; margin: 0 auto 20px; padding: 20px; background: #fff; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); box-sizing: border-box;" data-aos="fade-up">
  <form method="GET" action="" style="display: flex; flex-direction: column; gap: 12px;">
    <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
    <input type="number" name="min" placeholder="Precio mínimo" value="<?= $min ?>" style="padding: 12px; font-size: 16px; border: 2px solid #6c63ff; border-radius: 10px; box-sizing: border-box; width: 100%;">
    <input type="number" name="max" placeholder="Precio máximo" value="<?= $max ?>" style="padding: 12px; font-size: 16px; border: 2px solid #6c63ff; border-radius: 10px; box-sizing: border-box; width: 100%;">
    <button type="submit" style="padding: 10px 20px; background-color: #6c63ff; color: white; border: none; border-radius: 10px; font-weight: bold; cursor: pointer;">Filtrar</button>
  </form>
</div>





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
    <div style="margin-top: 20px;">
      <label for="textoCinta" style="font-weight: bold; display: block; margin-bottom: 10px;">Texto en la cinta personalizada:</label>
      <input type="text" id="textoCinta" placeholder="Escribe tu mensaje...valor adicional $10.000" oninput="actualizarTextoCinta()" style="padding: 10px; width: 100%; border: 2px solid #d63384; border-radius: 8px; font-size: 15px; box-sizing: border-box;">
    </div>

    <div style="margin-top: 20px; position: relative; display: inline-block;">
      <img src="privado/cinta_personalizada.png" id="imagenCinta" alt="Cinta personalizada" style="max-width: 100%; border-radius: 10px;">
      <div id="textoSobreCinta" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; font-size: 18px; color: black; text-shadow: 1px 1px 2px white;"></div>
    </div>

    <a id="botonWhatsappModal" class="boton-whatsapp" target="_blank">💐 Lo quiero con este color</a>
  </div>
</div>
<div id="barraWhatsapp" class="barra-fija-inferior whatsapp">

  📲 ¿Te gustó algún ramo? Haz clic en lo !quiero! y te llevaremos a WhatsApp con el nombre del producto listo para consultar ✨
</div>
<script>
let tituloActual = "";
let colorSeleccionado = "amarillo";

let textoCinta = "";

function actualizarTextoCinta() {
  textoCinta = document.getElementById("textoCinta").value;
  document.getElementById("textoSobreCinta").innerText = textoCinta;
  actualizarEnlaceWhatsapp();
}

function actualizarEnlaceWhatsapp() {
  const mensaje = `Quiero este ramo: ${tituloActual} en color ${colorSeleccionado}` +
                  (textoCinta ? ` con el texto: "${textoCinta}" en la cinta personalizada` : '');
  const enlace = `https://wa.me/573215116044?text=${encodeURIComponent(mensaje)}`;
  document.getElementById('botonWhatsappModal').href = enlace;
}

function abrirModal(titulo) {
  tituloActual = titulo;
  colorSeleccionado = "amarillo";
  textoCinta = "";
  document.getElementById("imagenRamo").src = "privado/flor amarilla.jpeg";
  document.getElementById("modalPersonalizar").style.display = "flex";
  document.body.style.overflow = "hidden";
  document.getElementById("textoCinta").value = "";
  document.getElementById("textoSobreCinta").innerText = "";
  document.getElementById("barraWhatsapp").style.display = "none"; // 👈 Oculta barra
  actualizarEnlaceWhatsapp();
}

function cerrarModal() {
  const modal = document.getElementById('modalPersonalizar');
  const contenido = modal.querySelector('.modal-contenido');

  contenido.classList.add('fade-out');

  setTimeout(() => {
    modal.style.display = 'none';
    contenido.classList.remove('fade-out');
    document.body.style.overflow = '';
    document.getElementById("barraWhatsapp").style.display = "block";
  }, 300); // debe coincidir con la duración de fadeOutModal
}



function cambiarImagen(ruta) {
  document.getElementById('imagenRamo').src = ruta;
  if (ruta.includes("amarillo")) colorSeleccionado = "amarillo";
  else if (ruta.includes("blanco")) colorSeleccionado = "blanco";
  actualizarEnlaceWhatsapp();
}

function actualizarEnlaceWhatsapp() {
  let mensaje = `Quiero este ramo: ${tituloActual} en color ${colorSeleccionado}`;

  if (textoCinta) {
    mensaje += ` con el texto: "${textoCinta}" en la cinta personalizada (costo adicional de $10.000)`;
  }

  const enlace = `https://wa.me/573215116044?text=${encodeURIComponent(mensaje)}`;
  document.getElementById('botonWhatsappModal').href = enlace;
}

</script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({ duration: 800, once: true });</script>

<script>
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
</script>
</body>
</html>
