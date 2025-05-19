<style>
  .circular-text {
    position: relative;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    animation: spin 20s linear infinite;
    margin: 0 auto;
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
    color: black;
    font-weight: bold;
    transform-origin: 0 0;
    pointer-events: none;
    line-height: 1;
  }
</style>

<div class="circular-text">
  <?php
    $text = "MELANY.VARIETIES.";
    $chars = str_split($text);
    $charCount = count($chars);
    $radius = 85;

    foreach ($chars as $i => $char) {
      $angle = $i * (360 / $charCount);
      echo "<span style='transform: rotate($angle"."deg) translate($radius"."px, -50%) rotate(-$angle"."deg);'>$char</span>";
    }
  ?>
</div>
