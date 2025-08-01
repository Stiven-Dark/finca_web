<?php
require_once 'bd/conexion.php';

// Recibir datos del formulario
$nombre       = $_POST['nombre'];
$apellido     = $_POST['apellido'];
$edad         = $_POST['edad'];
$ciudad       = $_POST['ciudad'];
$provincia    = $_POST['provincia'];
$departamento = $_POST['departamento'];
$telefono     = $_POST['telefono'];
$dni          = $_POST['dni'];
$interes      = $_POST['interes'];

// Estado inicial: en_espera
$estado = 'en_espera';
$asesor_id = NULL;

// Insertar en la base de datos
$stmt = $conexion->prepare("INSERT INTO clientes 
  (nombre, apellido, edad, ciudad,provincia, departamento, telefono, dni, interes, estado, asesor_id)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssisssssssi",  // ← 11 letras
  $nombre, $apellido, $edad, $ciudad, $provincia, $departamento,
  $telefono, $dni, $interes, $estado, $asesor_id
);

if ($stmt->execute()) {
    echo "<script>
      alert('Formulario enviado correctamente');
      window.location.href = '../index.php';
    </script>";
} else {
    echo "<script>
      alert('Error al guardar los datos');
      window.history.back();
    </script>";
}

$stmt->close();
$conexion->close();
?>
