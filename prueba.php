<?php
include 'conexion.php';

$sql = "SELECT DISTINCT categoria FROM catalogo_ramos";
$result = $conn->query($sql);

// Leer imágenes locales desde carpeta uploads/
$carpeta = 'uploads/';
$imagenes_disponibles = glob($carpeta . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
shuffle($imagenes_disponibles);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>✨ Catálogo Mágico de Flores ✨</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 25%, #ff9a9e 50%, #fecfef 75%, #fecfef 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            position: relative;
        }

        /* Animaciones principales */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-10px) rotate(1deg); }
            50% { transform: translateY(-5px) rotate(-1deg); }
            75% { transform: translateY(-15px) rotate(0.5deg); }
        }

        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0) rotate(0deg); }
            50% { opacity: 1; transform: scale(1) rotate(180deg); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleIn {
            0% { opacity: 0; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes levitate {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        /* Partículas flotantes */
        .particle-field::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, rgba(255,255,255,0.3), transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,182,193,0.4), transparent),
                radial-gradient(1px 1px at 90px 40px, rgba(255,255,255,0.5), transparent),
                radial-gradient(1px 1px at 130px 80px, rgba(255,182,193,0.3), transparent);
            background-repeat: repeat;
            background-size: 150px 100px;
            animation: float 6s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }

        /* Container principal */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Header mágico */
        .magic-header {
            text-align: center;
            margin-bottom: 80px;
            position: relative;
        }

        .magic-title {
            font-size: clamp(3rem, 8vw, 8rem);
            font-weight: 800;
            background: linear-gradient(45deg, #e91e63, #9c27b0, #673ab7, #3f51b5, #2196f3);
            background-size: 400% 400%;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 3s ease-in-out infinite, float 4s ease-in-out infinite;
            margin-bottom: 30px;
            text-shadow: 0 0 30px rgba(233, 30, 99, 0.3);
            font-family: 'Dancing Script', cursive;
        }

        .magic-subtitle {
            font-size: 1.5rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto 40px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 20px 40px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: fadeInUp 1.5s ease-out 1s both;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .decorative-line {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 40px 0;
        }

        .line {
            width: 60px;
            height: 2px;
            background: linear-gradient(to right, transparent, #e91e63);
            animation: pulse 2s infinite;
        }

        .magic-icon {
            margin: 0 20px;
            width: 40px;
            height: 40px;
            color: #e91e63;
            animation: levitate 3s ease-in-out infinite;
        }

        /* Grid de categorías */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            margin-bottom: 80px;
        }

        /* Tarjetas mágicas */
        .magic-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            animation: scaleIn 0.8s ease-out both;
        }

        .magic-card:hover {
            transform: translateY(-20px) scale(1.05);
            box-shadow: 
                0 40px 80px rgba(0, 0, 0, 0.2),
                0 0 50px rgba(233, 30, 99, 0.3);
            border-color: rgba(233, 30, 99, 0.4);
        }

        .magic-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.8s;
        }

        .magic-card:hover::before {
            left: 100%;
        }

        .card-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 30px 30px 0 0;
            transition: transform 0.5s ease;
        }

        .magic-card:hover .card-image {
            transform: scale(1.1);
        }

        .card-content {
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(45deg, #e91e63, #9c27b0);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: capitalize;
        }

        .magic-button {
            background: linear-gradient(45deg, #e91e63, #9c27b0);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(233, 30, 99, 0.3);
            position: relative;
            overflow: hidden;
        }

        .magic-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .magic-button:hover::before {
            left: 100%;
        }

        .magic-button:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(233, 30, 99, 0.5);
        }

        /* Tarjeta especial "Ver Todos" */
        .special-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: relative;
        }

        .special-card::after {
            content: '✨';
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 2rem;
            animation: sparkle 2s infinite;
        }

        /* Texto final mágico */
        .magic-footer {
            text-align: center;
            margin-top: 80px;
            animation: fadeInUp 1.5s ease-out 2s both;
        }

        .footer-text {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 25px 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: inline-block;
            font-size: 1.3rem;
            font-style: italic;
            color: #666;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .categories-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .magic-title {
                font-size: 3rem;
            }
            
            .magic-subtitle {
                font-size: 1.2rem;
                padding: 15px 25px;
            }
        }

        /* Efectos adicionales */
        .animate-delay-1 { animation-delay: 0.2s; }
        .animate-delay-2 { animation-delay: 0.4s; }
        .animate-delay-3 { animation-delay: 0.6s; }
        .animate-delay-4 { animation-delay: 0.8s; }
        .animate-delay-5 { animation-delay: 1s; }
    </style>
</head>

<body class="particle-field">
    <div class="container">
        <!-- Header Mágico -->
        <div class="magic-header" data-aos="fade-down" data-aos-duration="1500">
            <h1 class="magic-title">✨ Categorías Mágicas ✨</h1>
            
            <div class="decorative-line">
                <div class="line"></div>
                <i class="magic-icon" data-lucide="wand-2"></i>
                <div class="line" style="background: linear-gradient(to left, transparent, #e91e63);"></div>
            </div>
            
            <p class="magic-subtitle">
                Descubre un universo de belleza floral donde cada pétalo cuenta una historia única y mágica
            </p>
        </div>

        <!-- Grid de Categorías -->
        <div class="categories-grid">
            <!-- Tarjeta especial "Ver Todos" -->
            <div class="magic-card special-card animate-delay-1" 
                 data-aos="zoom-in" 
                 data-aos-duration="800"
                 onclick="location.href='ver_categoria.php'">
                <img src="https://images.pexels.com/photos/931162/pexels-photo-931162.jpeg" 
                     alt="Ver Todos" 
                     class="card-image">
                <div class="card-content">
                    <h3 class="card-title" style="color: white;">Ver Todos</h3>
                    <button class="magic-button">Explorar Colección</button>
                </div>
            </div>

            <?php
            $i = 0;
            $delay = 2;
            while($row = $result->fetch_assoc()):
                $categoria = htmlspecialchars($row['categoria']);
                $img = count($imagenes_disponibles) > 0 ? $imagenes_disponibles[$i % count($imagenes_disponibles)] : 'https://images.pexels.com/photos/1458736/pexels-photo-1458736.jpeg';
                $i++;
                $delay++;
            ?>
                <div class="magic-card animate-delay-<?= min($delay, 5) ?>" 
                     data-aos="zoom-in" 
                     data-aos-duration="800"
                     data-aos-delay="<?= $delay * 100 ?>"
                     onclick="location.href='ver_categoria.php?categoria=<?= urlencode($categoria) ?>'">
                    <img src="<?= $img ?>" 
                         alt="<?= $categoria ?>" 
                         class="card-image">
                    <div class="card-content">
                        <h3 class="card-title"><?= $categoria ?></h3>
                        <button class="magic-button">Descubrir Magia</button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Texto Final Mágico -->
        <div class="magic-footer">
            <div class="footer-text">
                ✨ Donde cada flor cuenta una historia de amor eterno ✨
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inicializar AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Inicializar Lucide Icons
        lucide.createIcons();

        // Efecto de mouse parallax
        document.addEventListener('mousemove', (e) => {
            const cards = document.querySelectorAll('.magic-card');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            cards.forEach((card, index) => {
                const speed = (index + 1) * 0.5;
                const x = (mouseX - 0.5) * speed;
                const y = (mouseY - 0.5) * speed;
                
                card.style.transform += ` translate(${x}px, ${y}px)`;
            });
        });

        // Efecto de sparkles aleatorios
        function createSparkle() {
            const sparkle = document.createElement('div');
            sparkle.innerHTML = '✨';
            sparkle.style.position = 'fixed';
            sparkle.style.left = Math.random() * window.innerWidth + 'px';
            sparkle.style.top = Math.random() * window.innerHeight + 'px';
            sparkle.style.fontSize = Math.random() * 20 + 10 + 'px';
            sparkle.style.opacity = '0';
            sparkle.style.pointerEvents = 'none';
            sparkle.style.zIndex = '1000';
            sparkle.style.animation = 'sparkle 3s ease-out forwards';
            
            document.body.appendChild(sparkle);
            
            setTimeout(() => {
                sparkle.remove();
            }, 3000);
        }

        // Crear sparkles cada 2 segundos
        setInterval(createSparkle, 2000);

        // Efecto de hover en las tarjetas
        document.querySelectorAll('.magic-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.zIndex = '10';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.zIndex = '1';
                this.style.transform = '';
            });
        });
    </script>
</body>
</html>
