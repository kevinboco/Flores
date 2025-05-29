<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Animación con Botones Separados</title>
  <link rel="stylesheet" href="style/style_index.css">
  <style>
    body {
      background: url('uploads/63a7cb6a-ba55-4583-9daf-971cd26453e5.jpeg') no-repeat center center;
      background-size: contain;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      height: 100vh;
      margin: 0;
      padding: 2em 1em 4em;
      /* padding abajo para espacio de botones */
      font-family: 'Compressa VF', sans-serif;
    }

    /* Contenedor fijo para el texto animado */
    .text-container {
      width: 100%;
      max-width: 900px;
      min-height: 20vw;
      /* fija la altura mínima para evitar que cambie con escala */
      display: flex;
      justify-content: center;
      margin-bottom: 3em;
      /* espacio fijo antes de botones */
    }

    .text-pressure {
      font-family: 'Compressa VF';
      font-size: 10vw;
      text-transform: uppercase;
      display: flex;
      gap: 0.1em;
      /* Para que no cambie el tamaño del contenedor al hacer scale */
      transform-origin: center bottom;
    }

    .text-pressure span {
      font-variation-settings: 'wght' 100, 'wdth' 50, 'ital' 0;
      display: inline-block;
      animation: pulse 10s ease-in-out infinite;
    }

    .text-pressure.stroke span {
      color: white;
      position: relative;
    }

    .text-pressure.stroke span::after {
      content: attr(data-char);
      position: absolute;
      left: 0;
      top: 0;
      color: transparent;
      -webkit-text-stroke: 2px black;
      z-index: -1;
    }

    @keyframes pulse {
      0% {
        font-variation-settings: 'wght' 100, 'wdth' 50, 'ital' 0;
        transform: scale(1);
      }

      50% {
        font-variation-settings: 'wght' 500, 'wdth' 200, 'ital' 1;
        transform: scale(1.2);
      }

      100% {
        font-variation-settings: 'wght' 100, 'wdth' 50, 'ital' 0;
        transform: scale(0.5);
      }
    }

    /* Contenedor de botones separado */
    .buttons-container {
      display: flex;
      gap: 1em;
      justify-content: center;
      width: 100%;
      max-width: 900px;
    }

    .animated-button {
      background: linear-gradient(135deg, #6b8ce3, #4a90e2);
      color: white;
      border: none;
      padding: 14px 28px;
      font-size: 18px;
      font-weight: 600;
      border-radius: 12px;
      cursor: pointer;
      margin: 8px 0;
      box-shadow: 0 6px 12px rgba(74, 144, 226, 0.4);
      transition:
        transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
        box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1),
        background-position 0.5s ease;
      background-size: 200% 200%;
      background-position: left center;
      opacity: 0;
      transform: translateY(100px);
    }

    .animated-button:hover {
      transform: scale(1.08);
      box-shadow: 0 10px 20px rgba(74, 144, 226, 0.6);
      background-position: right center;
    }

    .animated-button:active {
      transform: scale(0.95);
      box-shadow: 0 4px 10px rgba(74, 144, 226, 0.8);
    }

    .animated-button.animate-up {
      opacity: 1;
      transform: translateY(0);
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
  

  <div class="text-container">
    <h1 class="text-pressure stroke">
      <span data-char="M">M</span>
      <span data-char="E">E</span>
      <span data-char="L">L</span>
      <span data-char="A">A</span>
      <span data-char="N">N</span>
      <span data-char="Y">Y</span>
      <span data-char=""> </span>
      <span data-char="V">V</span>
      <span data-char="A">A</span>
      <span data-char="R">R</span>
      <span data-char="I">I</span>
      <span data-char="E">E</span>
      <span data-char="D">D</span>
    </h1>
  </div>
  
  <div class="banner">
    <div class="slider" style="--quantity: 8">
      <div class="item" style="--position: 1"><img src="image/1.jpg" alt=""></div>
      <div class="item" style="--position: 2"><img src="image/2.jpg" alt=""></div>
      <div class="item" style="--position: 3"><img src="image/3.jpg" alt=""></div>
      <div class="item" style="--position: 4"><img src="image/4.jpg" alt=""></div>
      <div class="item" style="--position: 5"><img src="image/5.jpg" alt=""></div>
      
      <div class="item" style="--position: 6"><img src="image/6.jpg" alt=""></div>
      <div class="item" style="--position: 7"><img src="image/7.jpg" alt=""></div>
      <div class="item" style="--position: 8"><img src="image/8.jpg" alt=""></div>
    </div>

    <div class="content">
    <h1 data-content="">Rosas eternas</h1>
  </div>
  
  <div class="author">
    <h2>melany</h2>
    <p><b>ramos y fechas especiales</b></p>
    <p>En cada fecha especial, las flores no son solo un detalle: son un símbolo de cariño, un homenaje a la vida y una forma elegante de estar presente, incluso en la distancia.

</p>
  </div>
  <div class="model"></div>
  </div>
  
  <div style="margin-top: 2em; text-align: center;">
    <div style="display: inline-flex; gap: 1.5em; align-items: center;">
      <button class="btn neon-pulse" onclick="window.location.href='catalogousuario.php'">
        <img src="privado/catalogar2.png" alt="Buscar" width="32" height="32" />
        <span>catálogo</span>
      </button>
      <button class="btn neon-pulse" onclick="window.location.href='https://wa.me/573215116044?text=Hola%2C+estoy+interesado+en+el+catálogo'">
        <img src="privado/whatsapp.png" alt="Buscar" width="32" height="32" />
        <span>whatsapp</span>
      </button>
    </div>
  </div>






  <style>
    .dock-outer {
      transition: transform 0.6s ease-in-out;
    }
  </style>

  <script>
    window.addEventListener('load', () => {
      const dock = document.querySelector('.dock-outer');
      if (dock) {
        dock.style.transform = 'translateY(-40px)'; // súbelo 40px
      }
    });
  </script>

</body>

</html>