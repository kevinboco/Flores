<?php
// nav.php - Dock con HTML, CSS y JS puro
?>

<style>
.dock-outer {
  margin: 0 0.5rem;
  display: flex;
  max-width: 100%;
  align-items: center;
  position: relative;
  height: 80px; /* espacio para el dock */
  user-select: none;
}

.dock-panel {
  position: fixed;
  top: -10rem;
  bottom: 0.5rem;
  left: 50%;
    transform: translateX(-50%);
  display: flex;
  align-items: flex-end;
  gap: 1rem;
  border-radius: 1rem;
  background-color: #060606;
  border: 1px solid #222;
  padding: 0 0.5rem 0.5rem;
  height: 68px;
}

.dock-item {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  background-color: #060606;
  border: 1px solid #222;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
    0 2px 4px -1px rgba(0, 0, 0, 0.06);
  cursor: pointer;
  outline: none;
  width: 50px;
  height: 50px;
  transition: width 0.3s ease, height 0.3s ease;
}

.dock-item:hover,
.dock-item:focus {
  width: 70px;
  height: 70px;
  z-index: 10;
  
}

.dock-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
}

.dock-label {
  position: absolute;
  top: -1.5rem;
  left: 100%;
  white-space: nowrap;
  border-radius: 0.375rem;
  border: 1px solid #222;
  background-color: #060606;
  padding: 0.125rem 0.5rem;
  font-size: 0.75rem;
  color: #fff;
  transform: translateX(-50%);
  opacity: 0;
  pointer-events: none;
   transition: .5s; /* Transición suave */
     
    
    animation: pulseOut 2s ease-out infinite;
    opacity: 0;
}

.dock-item:hover .dock-label,
.dock-item:focus .dock-label {
  opacity: 1;
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

<div class="dock-outer" role="toolbar" aria-label="Application dock">
  <div class="dock-panel">
    
    <div class="dock-item" tabindex="0" role="button" aria-haspopup="true" aria-label="agregar nuevo ramo" onclick="window.open('https://wa.me/573215116044', '_blank')">
      <div class="dock-icon">
        <img src="privado/whatsapp.png" alt="Buscar" width="32" height="32" />
      </div>
      <div class="dock-label">escribir por whatsapp</div>
    </div>
    <div class="dock-item" tabindex="0" role="button" aria-haspopup="true" aria-label="catalogo" onclick="window.location.href='catalogousuario.php'">
      <div class="dock-icon">
        <img src="privado/catalogar2.png" alt="Configuración" width="32" height="32" />
      </div>
      <div class="dock-label">catalogo</div>
    </div>
  </div>
</div>

<script>
  // Para mejorar la experiencia, hacemos que al mover el mouse cerca, aumente el icono cercano

  const dock = document.querySelector('.dock-panel');
  const items = document.querySelectorAll('.dock-item');

  dock.addEventListener('mousemove', (e) => {
    const dockRect = dock.getBoundingClientRect();

    items.forEach(item => {
      const rect = item.getBoundingClientRect();
      const centerX = rect.left + rect.width / 2;
      const distance = Math.abs(e.clientX - centerX);

      // Distancia máxima para efecto de magnificación
      const maxDistance = 150;

      if (distance < maxDistance) {
        // Calculamos escala entre 1 y 1.4 según cercanía
        const scale = 1 + (1 - distance / maxDistance) * 0.4;
        item.style.width = ${50 * scale}px;
        item.style.height = ${50 * scale}px;
        item.style.zIndex = 1000 - distance; // Prioriza el que está más cerca
      } else {
        item.style.width = '50px';
        item.style.height = '50px';
        item.style.zIndex = 'auto';
      }
    });
  });

  dock.addEventListener('mouseleave', () => {
    items.forEach(item => {
      item.style.width = '50px';
      item.style.height = '50px';
      item.style.zIndex = 'auto';
    });
  });
</script>