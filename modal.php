<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Personalizar Ramo</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: sans-serif;
      margin: 0;
      padding: 0;
      background: #f6f6f6;
    }

    .boton-personalizar {
      display: inline-block;
      margin: 20px;
      padding: 12px 20px;
      background-color: #e91e63;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 999;
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
      max-width: 100%;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
      position: relative;
    }

    .cerrar {
      position: absolute;
      top: 10px; right: 15px;
      font-size: 22px;
      font-weight: bold;
      cursor: pointer;
    }

    .modal-contenido img {
      max-width: 100%;
      max-height: 60vh; /* Ajusta según tu gusto */
      object-fit: contain;
      display: block;
      margin: 0 auto;
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

    @media (max-width: 480px) {
      .boton-personalizar {
        width: 90%;
        font-size: 18px;
      }
    }
  </style>
</head>
<body>

<button class="boton-personalizar" onclick="abrirModal()">Colores disponibles</button>

<div class="modal" id="modalPersonalizar">
  <div class="modal-contenido">
    <span class="cerrar" onclick="cerrarModal()">&times;</span>
    <h3>Elige el color del ramo</h3>
    <img id="imagenRamo" src="privado/flor_blanco.jpeg" alt="Ramo">

    <div class="color-opciones">
      <div class="color-opcion" style="background:yellow;" onclick="cambiarImagen('privado/flor amarillo.jpeg')"></div>
      <div class="color-opcion" style="background:white;" onclick="cambiarImagen('privado/flor blanco.jpeg')"></div>
    </div>
  </div>
</div>

<script>
  function abrirModal() {
    document.getElementById('modalPersonalizar').style.display = 'flex';
    document.getElementById('imagenRamo').src = 'privado/flor amarillo.jpeg';
  }

  function cerrarModal() {
    document.getElementById('modalPersonalizar').style.display = 'none';
  }

  function cambiarImagen(ruta) {
    document.getElementById('imagenRamo').src = ruta;
  }
</script>

</body>
</html>
