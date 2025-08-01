<?php
$host = "localhost";
$usuario = "root";
$contrasena = ""; // Cambia si usas contraseña
$base_datos = "lafinca_db";

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
