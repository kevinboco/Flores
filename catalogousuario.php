<?php
include 'conexion.php';

// Obtener categorías disponibles
$categoriasDisponibles = [];
$catQuery = mysqli_query($conn, "SELECT DISTINCT categoria FROM catalogo_ramos");
while ($catRow = mysqli_fetch_assoc($catQuery)) {
  $categoriasDisponibles[] = $catRow['categoria'];
}

// Obtener filtro de categoría
$categoriaSeleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Preparar consulta con filtro si hay categoría
if ($categoriaSeleccionada && in_array($categoriaSeleccionada, $categoriasDisponibles)) {
  $stmt = mysqli_prepare($conn, "SELECT id, titulo, valor, description, imagen FROM catalogo_ramos WHERE categoria = ? ORDER BY id ASC");
  mysqli_stmt_bind_param($stmt, "s", $categoriaSeleccionada);
} else {
  $stmt = mysqli_prepare($conn, "SELECT id, titulo, valor, description, imagen FROM catalogo_ramos ORDER BY id ASC");
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$productos = [];
while ($row = mysqli_fetch_assoc($result)) {
  $productos[] = $row;
}
mysqli_close($conn);

// Función reutilizable para mostrar productos
function renderProducto($p, $modo = 'carousel') {
  $html = '<div class="item">';
  $html .= '<img src="uploads/' . $p['imagen'] . '" alt="' . htmlspecialchars($p['titulo']) . '">';
  $html .= '<div class="content">';

  if ($modo === 'carousel') {
    $html .= '<div class="author">Autor: MELANY</div>';
    $html .= '<div class="title">' . htmlspecialchars($p['titulo']) . '</div>';
    $html .= '<div class="topic">' . htmlspecialchars($p['valor']) . ' $</div>';
    $html .= '<div class="des">' . nl2br(htmlspecialchars($p['description'])) . '</div>';
    $html .= '<div class="buttons"><button>CONTACTAME</button><button>PEDIR</button></div>';
  } elseif ($modo === 'thumbnail') {
    $html .= '<div class="title">' . htmlspecialchars($p['titulo']) . '</div>';
    $html .= '<div class="description">' . substr(htmlspecialchars($p['description']), 0, 50) . '...</div>';
  }

  $html .= '</div></div>';
  return $html;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Catálogo Ramos</title>
  <link rel="stylesheet" href="style/style_catalg.css">
  <style>
    

    

    
    .form-container {
      background: #222;
      padding: 20px 30px;
      border-radius: 15px;
      max-width: 400px;
      width: 100%;
      box-sizing: border-box;
      margin: 40px auto;
      color: white;
    }

    .form-container h2 {
      margin-top: 0;
      text-align: center;
    }

    .form-container label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    .form-container select,
    .form-container input[type="number"],
    .form-container input[type="text"] {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border-radius: 6px;
      border: none;
      font-size: 14px;
    }

    .form-container input[type="checkbox"] {
      margin-right: 8px;
    }

    #precioTotal {
      margin-top: 20px;
      font-size: 18px;
      font-weight: bold;
      text-align: center;
    }

    #btnEnviar {
      margin-top: 25px;
      padding: 12px 25px;
      width: 100%;
      background: #25D366;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    #btnEnviar:hover {
      background: #1ebe57;
    }
    .filter-form {
      font-size: 30px;
      color:rgb(255, 0, 0);
      position: relative;
      z-index: 10;
      background: #white;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(255, 253, 253, 0);
       margin-top: -20px; /* Súbelo 20px, ajusta el valor según lo necesites */
    }
    .btn {
  position: relative;
  padding: 1.5rem 3rem;
  font-size: 1.1rem;
  font-weight: 600;
  color: #fff;
  background: none;
  border: none;
  cursor: pointer;
  overflow: hidden;
  transition: all 0.4s ease;
  min-width: 200px;
  z-index: 1;
}

.neon-pulse {
  background: #000;
  border: 2px solid #0ff;
  box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
  overflow: visible;
  left: 750px;   /* X: distancia desde el borde izquierdo */
  top: -550px; 
}

.neon-pulse::before,
.neon-pulse::after {
  content: "";
  position: absolute;
  inset: -4px;
  border: 2px solid #0ff;
  border-radius: inherit;
  animation: pulseOut 2s ease-out infinite;
  opacity: 0;
}

.neon-pulse::after {
  animation-delay: 1s;
}

@keyframes pulseOut {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  100% {
    transform: scale(1.5);
    opacity: 0;
  }
}

  </style>
</head>
<body>

  <header>
    <nav>
      <a href="index.php">Home</a>
      <a href="https://api.whatsapp.com/send/?phone=573215116044&text&type=phone_number&app_absent=0">Contacts</a>
      <a href="#">Info</a>
    </nav>
  </header>

  <!-- Filtro por categoría -->
  <div class="filter-form">
    <form method="get" action="">
      <label for="categoria">Filtrar por categoría:</label>
      <select name="categoria" id="categoria">
        <option value="">-- Todas --</option>
        <?php foreach ($categoriasDisponibles as $cat): ?>
          <option value="<?= htmlspecialchars($cat) ?>" <?= ($cat === $categoriaSeleccionada) ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit">Filtrar</button>
    </form>
  </div>

  <!-- Carrusel -->
  <div class="carousel">
    <div class="list">
      <?php
      for ($i = 0; $i < count($productos); $i += 2) {
        echo renderProducto($productos[$i], 'carousel');
        if (isset($productos[$i + 1])) {
          echo renderProducto($productos[$i + 1], 'carousel');
        }
      }
      ?>
    </div>
    <div class="thumbnail">
      <?php
      for ($i = 0; $i < count($productos); $i += 2) {
        echo renderProducto($productos[$i], 'thumbnail');
        if (isset($productos[$i + 1])) {
          echo renderProducto($productos[$i + 1], 'thumbnail');
        }
      }
      ?>
    </div>
    <div class="arrows">
      <button id="prev"><</button>
      <button id="next">></button>
    </div>
    <div class="time"></div>
  </div>

  <!-- Formulario Personalización -->
  <div class="form-container">
    <h2>Personaliza tu ramo</h2>
    <label for="color">Escoge el color:</label>
    <select id="color">
      <option value="rojo">Rojo</option>
      <option value="azul">Azul</option>
      <option value="negro">Negro</option>
      <option value="blanco">Blanco</option>
      <option value="rosado">Rosado</option>
    </select>

    <label><input type="checkbox" id="luces"> Incluir luces (+$10.000)</label>
    <label><input type="checkbox" id="oso"> Incluir oso (+$15.000)</label>
    <label><input type="checkbox" id="cinta"> Incluir cinta con mensaje (+$5.000)</label>
    <input type="text" id="mensajeCinta" placeholder="Escribe el mensaje para la cinta" disabled>

    <label><input type="checkbox" id="agregarFlores"> Especificar cantidad de flores (+$3.000 por flor)</label>
    <label for="cantidadFlores">¿Cuántas flores quieres?</label>
    <input type="number" id="cantidadFlores" value="1" min="1" disabled>

    <div id="precioTotal">Precio total: $0</div>
    <button id="btnEnviar">Enviar por WhatsApp</button>
  </div>
  <div style="display: inline-flex; gap: 1.5em; align-items: center;">
      
      <button id="btnLoQuiero" class="btn neon-pulse">
        <img src="privado/whatsapp.png" alt="Buscar" width="32" height="32" />
        <span>lo quiero</span>
      </button>
    
  </div>
  <script>
    document.getElementById('btnLoQuiero').onclick = function(e) {
      e.preventDefault();

      // Encuentra el slide visible (el primero en el DOM)
      const item = document.querySelector('.carousel .list .item');
      if (!item) return;

      // Obtén la imagen y el id (puedes guardar el id como data-id en el div.item desde PHP)
      const img = item.querySelector('img');
      const id = item.getAttribute('data-id') || '';

      // Construye el mensaje
      let texto = `Hola, quiero este ramo (ID: ${id})%0A`;
      if (img) {
        texto += `Foto: ${window.location.origin}/${img.getAttribute('src')}%0A`;
      }

      texto += "¿Está disponible?";

      // Redirige a WhatsApp
      const telefono = "573215116044";
      const url = `https://wa.me/${telefono}?text=${texto}`;
      window.open(url, '_blank');
    };
    function calcularPrecio() {
      let base = 20000;
      if (document.getElementById('luces').checked) base += 10000;
      if (document.getElementById('oso').checked) base += 15000;
      if (document.getElementById('cinta').checked) base += 5000;
      if (document.getElementById('agregarFlores').checked) {
        const cantidad = parseInt(document.getElementById('cantidadFlores').value) || 0;
        base += cantidad * 3000;
      }
      document.getElementById('precioTotal').innerText = "Precio total: $" + base.toLocaleString();
      return base;
    }

    document.getElementById('cinta').addEventListener('change', function () {
      document.getElementById('mensajeCinta').disabled = !this.checked;
    });

    document.getElementById('agregarFlores').addEventListener('change', function () {
      document.getElementById('cantidadFlores').disabled = !this.checked;
    });

    document.querySelectorAll('input, select').forEach(el => {
      el.addEventListener('input', calcularPrecio);
      el.addEventListener('change', calcularPrecio);
    });

    calcularPrecio();

    document.getElementById('btnEnviar').addEventListener('click', () => {
      const color = document.getElementById('color').value;
      const luces = document.getElementById('luces').checked ? "Sí" : "No";
      const oso = document.getElementById('oso').checked ? "Sí" : "No";
      const cinta = document.getElementById('cinta').checked ? "Sí" : "No";
      const mensajeCinta = cinta ? document.getElementById('mensajeCinta').value.trim() : "N/A";
      const agregarFlores = document.getElementById('agregarFlores').checked;
      const cantidadFlores = agregarFlores ? document.getElementById('cantidadFlores').value : "No especificado";
      const precio = calcularPrecio();

      let texto = `Hola, quiero pedir un ramo con las siguientes características:%0A`;
      texto += `- Color: ${color}%0A`;
      texto += `- Luces: ${luces}%0A`;
      texto += `- Oso: ${oso}%0A`;
      texto += `- Cinta: ${cinta}%0A`;
      if (cinta) texto += `- Mensaje en la cinta: ${mensajeCinta}%0A`;
      texto += `- Cantidad de flores: ${cantidadFlores}%0A`;
      texto += `- Precio total: $${precio.toLocaleString()}%0A`;
      texto += `%0AGracias!`;

      const telefono = "573215116044";
      const url = `https://wa.me/${telefono}?text=${texto}`;
      window.open(url, '_blank');
    });
  </script>

  <script src="javascript/app.js"></script>
</body>
</html>
