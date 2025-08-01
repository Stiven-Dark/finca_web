const modal = document.getElementById("modal");
const btnAbrir = document.getElementById("abrirModal");
const btnCerrar = document.querySelector(".cerrar");

if (btnAbrir) {
  btnAbrir.onclick = () => modal.style.display = "block";
}
if (btnCerrar) {
  btnCerrar.onclick = () => modal.style.display = "none";
}
window.onclick = (e) => {
  if (e.target === modal) modal.style.display = "none";
}
