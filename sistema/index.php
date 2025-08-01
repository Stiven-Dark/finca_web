<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Formulario Cliente</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<button id="abrirModal">Registrar Cliente</button>

<div id="modal" class="modal">
  <div class="modal-contenido">
    <span class="cerrar">&times;</span>
    <h2>Formulario de Registro</h2>
    <form action="guardar_cliente.php" method="POST">
      <input type="text" name="nombre" placeholder="Nombre" required>
      <input type="text" name="apellido" placeholder="Apellido" required>
      <input type="number" name="edad" placeholder="Edad" required>
      <input type="text" name="ciudad" placeholder="Ciudad" required>
      <input type="text" name="departamento" placeholder="Departamento" required>
      <input type="text" name="telefono" placeholder="Teléfono" required>
      <input type="text" name="dni" placeholder="DNI" required>
      <select name="interes" required>
        <option value="">¿Qué busca?</option>
        <option value="Lote">Lote</option>
        <option value="Casa de playa">Casa de playa</option>
        <option value="Otro">Otro</option>
      </select>
      <button type="submit">Guardar</button>
    </form>
  </div>
</div>

<script src="js/modal.js"></script>
</body>
</html>
