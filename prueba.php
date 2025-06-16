<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Categorías Mágicas</title>

  <!-- Google Fonts: Fredoka -->
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Fredoka', sans-serif;
      background: linear-gradient(to bottom right, #ffe4ec, #f3e5f5);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      overflow: hidden;
    }

    .container {
      text-align: center;
      animation: fadeInUp 1.2s ease both;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    h1 {
      font-size: 3.2rem;
      font-weight: 700;
      background: linear-gradient(to right, #e11d48, #db2777, #9333ea, #7c3aed);
      background-clip: text;
      -webkit-background-clip: text;
      color: transparent;
      display: inline-block;
      margin: 0 20px;
    }

    .blob {
      width: 60px;
      height: 60px;
      background: linear-gradient(to right, #e11d48, #7c3aed);
      border-radius: 40% 60% 60% 40% / 50% 50% 60% 40%;
      display: inline-block;
      margin: 0 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
      animation: float 4s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% {
        transform: translateY(0px);
      }
      50% {
        transform: translateY(-12px);
      }
    }

    .decoracion {
      margin-top: 30px;
      font-size: 2rem;
      color: #f43f5e;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
        opacity: 1;
      }
      50% {
        transform: scale(1.1);
        opacity: 0.7;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div>
      <span class="blob"></span>
      <h1>Categorías<br>Mágicas</h1>
      <span class="blob"></span>
    </div>
    <div class="decoracion">✨</div>
  </div>
</body>
</html>
