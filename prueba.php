<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Texto Circular Girando</title>
  <style>
    body {
      background-color: #111;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .circular-text {
      position: relative;
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background: ;
      animation: spin 20s linear infinite;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    .circular-text span {
      position: absolute;
      top: 50%;
      left: 50%;
      font-size: 18px;
      color: white;
      font-weight: bold;
      transform-origin: 0 0;
      pointer-events: none;
      line-height: 1;
    }
  </style>
</head>
<body>
  <div class="circular-text">
    <?php
      $text = "MELANY.VARIETIES.";
      $chars = str_split($text);
      $charCount = count($chars);
      $radius = 85; // Ajusta esta distancia para acercar o alejar las letras al centro

      foreach ($chars as $i => $char) {
        $angle = $i * (360 / $charCount);
        echo "<span style='transform: rotate($angle"."deg) translate($radius"."px, -50%) rotate(-$angle"."deg);'>$char</span>";
      }
    ?>
  </div>
</body>
</html>
