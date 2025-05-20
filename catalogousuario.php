<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Carrusel y Pedido Personalizado</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #111;
      font-family: sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
      color: white;
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
      border: 5px solid white;
      border-radius: 20px;
      margin-bottom: 30px;
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
    button.back-button {
      margin: 15px;
      padding: 10px 20px;
      cursor: pointer;
      font-size: 16px;
      background: white;
      color: black;
      border: none;
      border-radius: 8px;
    }
    .form-container {
      background: #222;
      padding: 20px 30px;
      border-radius: 15px;
      max-width: 400px;
      width: 100%;
      box-sizing: border-box;
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
    const walk = (x - startX) * 2;
    carousel.scrollLeft = scrollLeft - walk;
  });

  function calcularPrecio() {
    let base = 20000; // precio base del ramo

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

</body>
</html>
