<?php
require_once 'bd/conexion.php';
header('Content-Type: application/json');

// Validar datos
if (!isset($_POST['cliente_id']) || !isset($_POST['asesor_id'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$cliente_id = intval($_POST['cliente_id']);
$asesor_id  = intval($_POST['asesor_id']);

try {
    // 1. Actualizar el cliente
    $stmt = $conexion->prepare("UPDATE clientes SET estado = 'atendido', asesor_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $asesor_id, $cliente_id);
    $stmt->execute();
    $stmt->close();

    // 2. Obtener información del cliente y asesor
    $cliente = $conexion->query("SELECT nombre, apellido, telefono, ciudad, provincia, departamento, interes FROM clientes WHERE id = $cliente_id")->fetch_assoc();
    $asesor = $conexion->query("SELECT nombre, telefono FROM vendedor WHERE id = $asesor_id")->fetch_assoc();

    $nombreCliente = $cliente['nombre'] . ' ' . $cliente['apellido'];
    $mensaje = "Hola {$asesor['nombre']},\n\n"
             . "Se te ha asignado un nuevo cliente:\n\n"
             . "👤 Nombre: $nombreCliente\n"
             . "📱 Teléfono: {$cliente['telefono']}\n"
             . "📍 Ciudad: {$cliente['ciudad']}\n"
             . "📍 Provincia: {$cliente['provincia']}\n"
             . "🗺 Departamento: {$cliente['departamento']}\n"
             . "🏡 Interés: {$cliente['interes']}\n\n"
             . "Por favor, comunícate con él a la brevedad.";

    // 3. Enviar WhatsApp
    enviarWhatsApp($asesor['telefono'], $mensaje);

    // ✅ Respuesta de éxito
    echo json_encode(['success' => true, 'message' => 'Asesor asignado y notificado por WhatsApp.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// ✅ Función WhatsApp
function enviarWhatsApp($telefono, $mensaje) {
    $token = 'm2b2lenerza4x0zq';
    $instanceId = 'instance131470';
    $url = "https://api.ultramsg.com/$instanceId/messages/chat";

    $data = [
        'token' => $token,
        'to' => $telefono,
        'body' => $mensaje
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data)
    ]);
    curl_exec($ch);
    curl_close($ch);
}