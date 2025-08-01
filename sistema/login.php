<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login | La Finca</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>

  <div class="login-box">
    <h2>Iniciar Sesión</h2>
    <form action="verificar_login.php" method="POST">
      <input type="email" name="correo" placeholder="Correo" required>
      <input type="password" name="contrasena" placeholder="Contraseña" required>
      <button type="submit">Ingresar</button>
    </form>

    <?php if (isset($_GET['error'])): ?>
      <p class="error">Correo o contraseña incorrectos</p>
    <?php endif; ?>
  </div>

</body>
</html>