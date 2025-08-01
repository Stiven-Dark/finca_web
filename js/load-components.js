// Cargar Header y Footer automáticamente
document.addEventListener("DOMContentLoaded", () => {
  // Cargar Header
  fetch('components/header.html')
    .then(response => response.text())
    .then(data => {
      const header = document.getElementById('main-header');
      if (header) header.innerHTML = data;
    });

  // Cargar Footer
  fetch('components/footer.html')
    .then(response => response.text())
    .then(data => {
      const footer = document.getElementById('main-footer');
      if (footer) footer.innerHTML = data;
    });
});

  document.addEventListener("DOMContentLoaded", function () {
    // Espera a que el header cargue
    fetch("components/header.html")
      .then(res => res.text())
      .then(data => {
        document.getElementById("main-header").innerHTML = data;

        // Ahora que el header está en el DOM, conecta el botón al modal
        const modal = document.getElementById("modal");
        const btnAbrir = document.getElementById("abrirModal");
        const btnCerrar = document.querySelector(".cerrar");

        if (btnAbrir) {
          btnAbrir.onclick = () => modal.style.display = "block";
        }
        if (btnCerrar) {
          btnCerrar.onclick = () => modal.style.display = "none";
        }

        window.onclick = function (event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        };
      });
  });
