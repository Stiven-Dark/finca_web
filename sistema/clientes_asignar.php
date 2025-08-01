<?php
require_once 'conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id = $_POST['cliente_id'];
    $vendedor_id = $_POST['vendedor_id'];

    $stmt = $conexion->prepare("UPDATE clientes SET vendedor_id = ?, estado = 'atendido' WHERE id = ?");
    $stmt->bind_param("ii", $vendedor_id, $cliente_id);
    $stmt->execute();
    $stmt->close();

    // Notificación futura aquí por WhatsApp o email

    header("Location: panel_admin.php");
}
?>
