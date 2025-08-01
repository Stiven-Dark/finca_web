// HERO SLIDER AUTOPLAY CADA 3 SEGUNDOS
document.addEventListener("DOMContentLoaded", () => {
  const slides = document.querySelectorAll('.slide');
  let currentSlide = 0;

  const maxSlides = 3;

  function showNextSlide() {
    const nextSlide = (currentSlide + 1) % maxSlides; // solo rota los primeros 3
    slides[nextSlide].classList.add('active');
    slides[currentSlide].classList.remove('active');
    currentSlide = nextSlide;
  }


  setInterval(showNextSlide, 5000); // cada 3 segundos
});

//  JS para alerta al enviar
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".contact-form");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault(); // evita recarga
      alert("Gracias por contactarnos. Te responderemos pronto.");
      form.reset();
    });
  }
});

/*NOSOTRO*/
let slides = document.querySelectorAll('#carrusel .slide');
let current = 0;

setInterval(() => {
  slides[current].classList.remove('active');
  current = (current + 1) % slides.length;
  slides[current].classList.add('active');
}, 3000);