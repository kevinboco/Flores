<?php
// nav.php - Dock responsive para móviles y escritorio
?>

<style>
/* Dock general */
.dock-outer {
  margin: 0;
  display: flex;
  justify-content: center;
  position: fixed;
  bottom: 0;
  width: 100%;
  z-index: 1000;
  user-select: none;
  pointer-events: none;
}

.dock-panel {
  display: flex;
  align-items: flex-end;
  gap: 1.5rem;
  background-color: #060606ee;
  border-radius: 1.2rem;
  border: 1px solid #222;
  padding: 1rem 2rem;
  pointer-events: auto;
}

/* Ítems */
.dock-item {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #060606;
  border: 1px solid #222;
  border-radius: 1rem;
  cursor: pointer;
  width: 70px;
  height: 70px;
  transition: transform 0.2s;
}

.dock-item:hover {
  transform: scale(1.1);
}

.dock-icon img {
  width: 40px;
  height: 40px;
}

.dock-label {
  position: absolute;
  top: -2rem;
  left: 50%;
  transform: translateX(-50%);
  background-color: #060606;
  border: 1px solid #222;
  padding: 0.3rem 0.8rem;
  border-radius: 0.5rem;
  font-size: 1rem;
  color: white;
  white-space: nowrap;
  opacity: 0;
  transition: opacity 0.3s;
}

.dock-item:hover .dock-label {
  opacity: 1;
}

/* Estilo móvil */
@media (max-width: 768px) {
  .dock-panel {
    gap: 1rem;
    padding: 0.8rem 1.2rem;
  }

  .dock-item {
    width: 80px;
    height: 80px;
  }

  .dock-icon img {
    width: 50px;
    height: 50px;
  }

  .dock-label {
    font-size: 0.9rem;
  }
}
</style>

<div class="dock-outer">
  <div class="dock-panel">
    
    <div class="dock-item" onclick="window.open('https://wa.me/573215116044', '_blank')">
      <div class="dock-icon">
        <img src="privado/whatsapp.png" alt="WhatsApp">
      </div>
      <div class="dock-label">WhatsApp</div>
    </div>

    <div class="dock-item" onclick="window.location.href='catalogousuario.php'">
      <div class="dock-icon">
        <img src="privado/catalogar2.png" alt="Catálogo">
      </div>
      <div class="dock-label">Catálogo</div>
    </div>
  </div>
</div>
