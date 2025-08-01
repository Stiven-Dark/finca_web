<?php 
require_once 'bd/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener asesores de la tabla "vendedor"
$asesores_resultado = $conexion->query("SELECT id, CONCAT(nombre, ' ', apellido) AS nombre FROM vendedor WHERE estado = 1");
$asesores = [];
while ($a = $asesores_resultado->fetch_assoc()) {
    $asesores[] = $a;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }
        .main-content {
            margin-left: 240px;
            padding: 30px;
        }
        h2 {
            color: #294129;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        thead {
            background-color: #3E6C3E;
            color: white;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        td:last-child {
            font-weight: 500;
        }
        .boton-asignar {
            background-color: #3E6C3E;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .boton-asignar:hover {
            background-color: #2f4f2f;
            transform: scale(1.05);
        }
        .modal {
            display: none;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .modal.mostrar {
            display: block;
            opacity: 1;
            pointer-events: auto;
        }
        .modal-content {
            background: #fff;
            width: 300px;
            margin: 10% auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            text-align: center;
            animation: fadeSlide 0.4s ease forwards;
            opacity: 0;
        }
        .modal-content h3 {
            margin-bottom: 15px;
            font-size: 20px;
            color: #2e4a2e;
        }
        .modal-content select, .modal-content button {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            font-size: 14px;
        }
        .modal-content select {
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }
        .modal-content button[type="submit"] {
            background-color: #3E6C3E;
            color: white;
            border: none;
            margin-bottom: 10px;
        }
        .modal-content button[type="button"] {
            background-color: #e0e0e0;
            color: #333;
            border: none;
        }
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #3E6C3E;
            color: white;
            padding: 14px 20px;
            border-radius: 8px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.2);
            z-index: 9999;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        .toast.hidden {
            display: none;
        }
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h2>Gestión de Clientes</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Nombre</th><th>DNI</th><th>Teléfono</th><th>Ciudad</th><th>Provincia</th>
                <th>Departamento</th><th>Edad</th><th>Interés</th><th>Estado</th><th>Asignación</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT c.*, CONCAT(v.nombre, ' ', v.apellido) AS asesor FROM clientes c 
                LEFT JOIN vendedor v ON c.asesor_id = v.id ORDER BY c.id DESC";
        $resultado = $conexion->query($sql);

        while ($fila = $resultado->fetch_assoc()) {
            $nombreCompleto = $fila['nombre'] . ' ' . $fila['apellido'];
            echo "<tr>
                <td>{$fila['id']}</td>
                <td>{$nombreCompleto}</td>
                <td>{$fila['dni']}</td>
                <td>{$fila['telefono']}</td>
                <td>{$fila['ciudad']}</td>
                <td>" . htmlspecialchars($fila['provincia'] ?? 'No definido') . "</td>
                <td>{$fila['departamento']}</td>
                <td>{$fila['edad']}</td>
                <td>{$fila['interes']}</td>
                <td><strong style='color:" . ($fila['estado'] === 'en_espera' ? "#b30000" : "#2b502b") . "'>" . strtoupper($fila['estado']) . "</strong></td>
                <td>";
            if ($fila['estado'] === 'en_espera') {
                echo "<button class='boton-asignar' onclick='abrirModal({$fila['id']}, \"{$fila['nombre']}\", \"{$fila['apellido']}\")'>Asignar</button>";
            } else {
                echo htmlspecialchars($fila['asesor'] ?? 'Sin asignar');
            }
            echo "</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- MODAL -->
<div id="modal" class="modal">
  <div class="modal-content">
    <h3>Asignar Asesor</h3>
    <p><strong>Cliente:</strong> <span id="nombre_completo_cliente"></span></p>
    <form id="formAsignar" method="POST" action="asignar_asesor.php">
      <input type="hidden" name="cliente_id" id="cliente_id_modal">
      <select name="asesor_id" required>
        <option value="">Seleccione un asesor</option>
        <?php foreach ($asesores as $a): ?>
          <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit">Asignar</button>
      <button type="button" onclick="cerrarModal()">Cancelar</button>
    </form>
  </div>
</div>

<div id="toast" class="toast hidden">Mensaje</div>

<script>
function abrirModal(clienteId, nombre = '', apellido = '') {
    document.getElementById("cliente_id_modal").value = clienteId;
    document.getElementById("nombre_completo_cliente").textContent = nombre + ' ' + apellido;
    document.getElementById("modal").classList.add("mostrar");
}
function cerrarModal() {
    document.getElementById("modal").classList.remove("mostrar");
}
function mostrarToast(mensaje) {
    const toast = document.getElementById('toast');
    toast.textContent = mensaje;
    toast.classList.remove('hidden');
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.add('hidden');
    }, 4000);
}
document.getElementById("formAsignar").addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch("asignar_asesor.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        mostrarToast(data.message || 'Asignado correctamente');
        if (data.success) {
            cerrarModal();
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(() => mostrarToast("Error en la conexión."));
});
</script>

</body>
</html>