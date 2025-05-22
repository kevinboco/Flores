<?php
// conexion.php debe contener la conexión a MySQL en $conn
include 'conexion.php';

// Ejecutar consulta y guardar resultados
$sql = "SELECT id, titulo, valor, description, imagen FROM catalogo_ramos ORDER BY id ASC";
$result = mysqli_query($conn, $sql);
if (!$result) {
  die("Error en la consulta: " . mysqli_error($conn));
}

$productos = [];
while ($row = mysqli_fetch_assoc($result)) {
  $productos[] = $row;
}
mysqli_close($conn);

// Función reutilizable para mostrar un producto
function renderProducto($p, $modo = 'carousel')
{
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
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

  <!-- ================================
       CARRUSEL PRINCIPAL (en pares)
       ================================ -->
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
    </div> <!-- /.list -->

    <!-- ================================
         MINIATURAS (también en pares)
         ================================ -->
    <div class="thumbnail">
      <?php
      for ($i = 0; $i < count($productos); $i += 2) {
        echo renderProducto($productos[$i], 'thumbnail');
        if (isset($productos[$i + 1])) {
          echo renderProducto($productos[$i + 1], 'thumbnail');
        }
      }
      ?>
    </div> <!-- /.thumbnail -->

    <!-- Controles de navegación -->
    <div class="arrows">
      <button id="prev">
        < </button>
          <button id="next">></button>
    </div>

    <!-- Barra de tiempo -->
    <div class="time"></div>
  </div> <!-- /.carousel -->


<!--
  <div class="catalogo">
    <?php foreach ($productos as $p): ?>
      <div class="producto">
        <img src="uploads/<?= htmlspecialchars($p['imagen'] ?? 'default.png') ?>" alt="<?= htmlspecialchars($p['titulo'] ?? 'Producto') ?>">

        <h3><?= htmlspecialchars($p['titulo']) ?></h3>
        <p><?= htmlspecialchars($p['description']) ?></p>
        <strong>$<?= number_format($p['valor'], 0, ',', '.') ?></strong>

        <form target="_blank" action="https://api.whatsapp.com/send" method="get">
          <input type="hidden" name="phone" value="573245123986">
          <input type="hidden" name="text"
            value="Hola, estoy interesado en '<?= htmlspecialchars($p['titulo']) ?>' con precio $<?= number_format($p['valor'], 0, ',', '.') ?>">
          <button type="submit">Pedir por WhatsApp</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div> -->





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




  <script>
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