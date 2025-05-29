<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>catalogo</title>
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #0a0a0a;
      font-family: "Inter", sans-serif;
      perspective: 1000px;
      overflow-x: hidden;
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
  <button class="btn neon-pulse">
    <span>catalogo</span>
  </button>
</body>
</html>
