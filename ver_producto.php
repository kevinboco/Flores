<?php
include 'conexion.php';

$id = $_GET['id'] ?? '';
$sql = "SELECT * FROM catalogo_ramos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
  echo "Producto no encontrado.";
  exit;
}

$titulo = htmlspecialchars($producto['titulo']);
$imagen = './uploads/' . htmlspecialchars($producto['imagen']);
$descripcion = htmlspecialchars($producto['description']);
$precio = number_format($producto['valor']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $titulo ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 20px;
      background: #fff0f6;
      color: #333;
    }

    .navbar {
      margin-bottom: 20px;
    }

    .navbar a {
      text-decoration: none;
      color: #d63384;
      font-weight: bold;
      font-size: 16px;
    }

    .producto {
      max-width: 800px;
      margin: auto;
      background: white;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .producto img {
      width: 100%;
      border-radius: 12px;
      margin-bottom: 20px;
    }

    .producto h1 {
      color: #d63384;
      margin-top: 0;
    }

    .producto p {
      font-size: 16px;
      margin: 10px 0;
    }

    .boton-whatsapp {
      display: inline-block;
      background: #25D366;
      color: white;
      padding: 10px 16px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      text-decoration: none;
    }

    .boton-whatsapp:hover {
      background: #1ebe5c;
    }
  </style>
</head>
<body>

  <div class="navbar">
    <a href="javascript:history.back()">← Volver</a>
  </div>

  <div class="producto">
    <img src="<?= $imagen ?>" alt="<?= $titulo ?>">
    <h1><?= $titulo ?></h1>
    <p><strong>Precio:</strong> $<?= $precio ?></p>
    <p><?= $descripcion ?></p>
    <a class="boton-whatsapp" href="https://wa.me/573215116044?text=Quiero este producto: <?= urlencode($titulo) ?>" target="_blank">💐 Lo quiero</a>
  </div>

</body>
</html>
