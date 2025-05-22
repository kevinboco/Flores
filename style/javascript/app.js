// Paso 1: Obtener elementos del DOM
let nextDom = document.getElementById('next'); // Botón "siguiente"
let prevDom = document.getElementById('prev'); // Botón "anterior"

let carouselDom = document.querySelector('.carousel'); // Contenedor del carrusel
let SliderDom = carouselDom.querySelector('.carousel .list'); // Lista principal de slides
let thumbnailBorderDom = document.querySelector('.carousel .thumbnail'); // Contenedor de miniaturas
let thumbnailItemsDom = thumbnailBorderDom.querySelectorAll('.item'); // Miniaturas individuales
let timeDom = document.querySelector('.carousel .time'); // Barra de progreso

// Mueve la primera miniatura al final para efecto de bucle
thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);

// Configuración de tiempos
let timeRunning = 3000;     // Tiempo que dura la animación
let timeAutoNext = 7000;    // Tiempo para avanzar automáticamente

// Eventos al hacer clic en los botones
nextDom.onclick = function(){
    showSlider('next'); // Muestra el siguiente slide
}
prevDom.onclick = function(){
    showSlider('prev'); // Muestra el slide anterior
}

// Avanza automáticamente al siguiente slide después de cierto tiempo
let runTimeOut;
let runNextAuto = setTimeout(() => {
    next.click();
}, timeAutoNext);

// Función para mostrar el slide
function showSlider(type){
    let SliderItemsDom = SliderDom.querySelectorAll('.carousel .list .item');
    let thumbnailItemsDom = document.querySelectorAll('.carousel .thumbnail .item');

    if(type === 'next'){
        // Mueve el primer slide al final
        SliderDom.appendChild(SliderItemsDom[0]);
        thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
        carouselDom.classList.add('next'); // Aplica animación de "siguiente"
    } else {
        // Mueve el último slide al inicio
        SliderDom.prepend(SliderItemsDom[SliderItemsDom.length - 1]);
        thumbnailBorderDom.prepend(thumbnailItemsDom[thumbnailItemsDom.length - 1]);
        carouselDom.classList.add('prev'); // Aplica animación de "anterior"
    }

    // Limpia clases después de la animación
    clearTimeout(runTimeOut);
    runTimeOut = setTimeout(() => {
        carouselDom.classList.remove('next');
        carouselDom.classList.remove('prev');
    }, timeRunning);

    // Reinicia el temporizador de avance automático
    clearTimeout(runNextAuto);
    runNextAuto = setTimeout(() => {
        next.click();
    }, timeAutoNext);
}
