<?php
// nav.php - Dock con íconos más grandes
?>

<style>
.dock-outer {
  margin: 0 0.5rem;
  display: flex;
  max-width: 100%;
  align-items: center;
  position: relative;
  height: 120px; /* espacio más alto */
  user-select: none;
}

.dock-panel {
  position: absolute;
  bottom: 1rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  align-items: flex-end;
  gap: 1.5rem;
  border-radius: 1rem;
  background-color: #060606;
  border: 1px solid #222;
  padding: 0 1rem 1rem;
  height: 100px;
}

.dock-item {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  background-color: #060606;
  border: 1px solid #222;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
    0 2px 4px -1px rgba(0, 0, 0, 0.06);
  cursor: pointer;
  outline: none;
  width: 70px;
  height: 70px;
  transition: width 0.3s ease, height 0.3s ease;
}

.dock-item:hover,
.dock-item:focus {
  width: 90px;
  height: 90px;
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
  top: -2rem;
  left: 50%;
  white-space: nowrap;
  border-radius: 0.5rem;
  border: 1px solid #222;
  background-color: #060606;
  padding: 0.25rem 0.75rem;
  font-size: 1rem;
  color: #fff;
  transform: translateX(-50%);
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.2s ease;
}

.dock-item:hover .dock-label,
.dock-item:focus .dock-label {
  opacity: 1;
}
</style>

<div class="dock-outer" role="toolbar" aria-label="Application dock">
  <div class="dock-panel">
    
    <div class="dock-item" tabindex="0" role="button" aria-haspopup="true" aria-label="agregar nuevo ramo" onclick="window.open('https://wa.me/573215116044', '_blank')">
      <div class="dock-icon">
        <img src="privado/whatsapp.png" alt="WhatsApp" width="48" height="48" />
      </div>
      <div class="dock-label">escribir por whatsapp</div>
    </div>

    <div class="dock-item" tabindex="0" role="button" aria-haspopup="true" aria-label="catalogo" onclick="window.location.href='catalogousuario.php'">
      <div class="dock-icon">
        <img src="privado/catalogar2.png" alt="Catálogo" width="48" height="48" />
      </div>
      <div class="dock-label">catálogo</div>
    </div>

  </div>
</div>

<script>
  const dock = document.querySelector('.dock-panel');
  const items = document.querySelectorAll('.dock-item');

  dock.addEventListener('mousemove', (e) => {
    const dockRect = dock.getBoundingClientRect();

    items.forEach(item => {
      const rect = item.getBoundingClientRect();
      const centerX = rect.left + rect.width / 2;
      const distance = Math.abs(e.clientX - centerX);

      const maxDistance = 150;
      if (distance < maxDistance) {
        const scale = 1 + (1 - distance / maxDistance) * 0.4;
        item.style.width = `${70 * scale}px`;
        item.style.height = `${70 * scale}px`;
        item.style.zIndex = 1000 - distance;
      } else {
        item.style.width = '70px';
        item.style.height = '70px';
        item.style.zIndex = 'auto';
      }
    });
  });

  dock.addEventListener('mouseleave', () => {
    items.forEach(item => {
      item.style.width = '70px';
      item.style.height = '70px';
      item.style.zIndex = 'auto';
    });
  });
</script>
