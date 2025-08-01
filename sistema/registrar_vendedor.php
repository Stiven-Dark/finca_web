<?php
require_once 'bd/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Insertar nuevo vendedor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'registrar') {
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $tipo_venta = $_POST['tipo_venta'];
    $estado = 1;

    $stmt = $conexion->prepare("INSERT INTO vendedor (nombre, apellido, dni, telefono, tipo_venta, estado) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nombres, $apellidos, $dni, $telefono, $tipo_venta, $estado);
    $stmt->execute();
    $stmt->close();
    $mensaje = "Vendedor registrado correctamente";
}

// Editar vendedor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $id = $_POST['id_editar'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $tipo_venta = $_POST['tipo_venta'];

    $stmt = $conexion->prepare("UPDATE vendedor SET nombre = ?, apellido = ?, dni = ?, telefono = ?, tipo_venta = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $nombres, $apellidos, $dni, $telefono, $tipo_venta, $id);
    $stmt->execute();
    $stmt->close();
    $mensaje = "Vendedor actualizado correctamente";
}

// Eliminar vendedor
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conexion->query("DELETE FROM vendedor WHERE id = $id");
    $mensaje = "Vendedor eliminado correctamente";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Vendedores</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7f9;
        }

        .main-content {
            margin-left: 240px;
            padding: 30px;
        }

        h2, h3 {
            color: #294129;
            margin-bottom: 20px;
        }

        .btn-flotante {
            background-color: #3E6C3E;
            color: white;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-bottom: 25px;
        }

        .btn-flotante:hover {
            background-color: #2b502b;
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
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .acciones button {
            background-color: #3E6C3E;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 5px;
        }

        .acciones button.eliminar {
            background-color: #b30000;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }

        .modal.mostrar {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            animation: slideDown 0.4s ease;
        }

        .modal-content input, .modal-content select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 14px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .modal-content button {
            width: 100%;
            padding: 10px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .modal-content .cancelar {
            background-color: #e0e0e0;
            margin-top: 10px;
        }

        .modal-content .cancelar:hover {
            background-color: #ccc;
        }

        .modal-content .guardar {
            background-color: #3E6C3E;
            color: white;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h2>Gestión de Vendedores</h2>

    <?php if (!empty($mensaje)) echo "<p style='color:green; font-weight:bold;'>$mensaje</p>"; ?>

    <button class="btn-flotante" onclick="abrirModal()">+ Nuevo Vendedor</button>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Nombre Completo</th><th>DNI</th><th>Teléfono</th><th>Tipo de Venta</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $result = $conexion->query("SELECT * FROM vendedor ORDER BY id DESC");
        while ($v = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$v['id']}</td>
                <td>{$v['nombre']} {$v['apellido']}</td>
                <td>{$v['dni']}</td>
                <td>{$v['telefono']}</td>
                <td>{$v['tipo_venta']}</td>
                <td class='acciones'>
                    <button onclick=\"editarVendedor(" . htmlspecialchars(json_encode($v), ENT_QUOTES) . ")\">Editar</button>
                    <button class='eliminar' onclick=\"confirmarEliminar({$v['id']})\">Eliminar</button>
                </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- MODAL -->
<div id="modalRegistro" class="modal">
    <div class="modal-content">
        <h3 id="modalTitulo">Registrar Vendedor</h3>
        <form method="POST" id="formVendedor">
            <input type="hidden" name="accion" id="accion" value="registrar">
            <input type="hidden" name="id_editar" id="id_editar">
            <input type="text" name="nombres" id="nombres" placeholder="Nombres" required>
            <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" required>
            <input type="text" name="dni" id="dni" placeholder="DNI" required>
            <input type="text" name="telefono" id="telefono" placeholder="Teléfono" required>
            <select name="tipo_venta" id="tipo_venta" required>
                <option value="">Tipo de venta</option>
                <option value="lote">Lote</option>
                <option value="casa">Casa</option>
                <option value="playa">Playa</option>
            </select>
            <button type="submit" class="guardar">Guardar</button>
            <button type="button" class="cancelar" onclick="cerrarModal()">Cancelar</button>
        </form>
    </div>
</div>

<script>
function abrirModal() {
    document.getElementById("modalTitulo").textContent = "Registrar Vendedor";
    document.getElementById("accion").value = "registrar";
    document.getElementById("formVendedor").reset();
    document.getElementById("modalRegistro").classList.add("mostrar");
}

function cerrarModal() {
    document.getElementById("modalRegistro").classList.remove("mostrar");
}

function editarVendedor(v) {
    document.getElementById("modalTitulo").textContent = "Editar Vendedor";
    document.getElementById("accion").value = "editar";
    document.getElementById("id_editar").value = v.id;
    document.getElementById("nombres").value = v.nombre;
    document.getElementById("apellidos").value = v.apellido;
    document.getElementById("dni").value = v.dni;
    document.getElementById("telefono").value = v.telefono;
    document.getElementById("tipo_venta").value = v.tipo_venta;
    document.getElementById("modalRegistro").classList.add("mostrar");
}

function confirmarEliminar(id) {
    if (confirm("¿Estás seguro de eliminar este vendedor?")) {
        window.location.href = "?eliminar=" + id;
    }
}
</script>

</body>
</html>