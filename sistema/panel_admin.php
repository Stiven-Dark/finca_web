<?php
require_once 'bd/conexion.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
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
            margin-bottom: 30px;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 25px 30px;
            min-width: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .card h3 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #3E6C3E;
        }

        .card p {
            font-size: 36px;
            font-weight: bold;
            color: #2b502b;
            margin: 0;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main-content">
    <h2>Resumen de Clientes</h2>

    <?php
    // Conteo de clientes en espera
    $consulta_espera = $conexion->query("SELECT COUNT(*) AS total FROM clientes WHERE estado = 'en_espera'");
    $espera = $consulta_espera->fetch_assoc()['total'];

    // Conteo de clientes atendidos
    $consulta_atendidos = $conexion->query("SELECT COUNT(*) AS total FROM clientes WHERE estado = 'atendido'");
    $atendidos = $consulta_atendidos->fetch_assoc()['total'];
    ?>

    <div class="card-container">
        <div class="card">
            <h3>🕒 En Espera</h3>
            <p><?= $espera ?></p>
        </div>
        <div class="card">
            <h3>✅ Atendidos</h3>
            <p><?= $atendidos ?></p>
        </div>
    </div>
</div>
</body>
</html>