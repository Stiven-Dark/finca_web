<?php
require_once 'bd/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Intereses válidos
$intereses = [
    "Proyecto Las Dunas De Carhuaz",
    "Proyecto La Planicie De Costa Sur",
    "Casa Vivienda Girasol",
    "Casa Vivienda Rosas",
    "Casa Vivienda Esmeralda"
];

// Construir dinámicamente los SUM() para cada interés
$intereses_sql = "";
foreach ($intereses as $i) {
    $alias = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($i));
    $intereses_sql .= ", SUM(CASE WHEN c.interes = '$i' THEN 1 ELSE 0 END) AS `$alias`";
}

// Consulta final
$sql = "
    SELECT 
        v.id,
        CONCAT(v.nombre, ' ', v.apellido) AS nombre_completo,
        COUNT(c.id) AS total_clientes
        $intereses_sql
    FROM vendedor v
    LEFT JOIN clientes c ON c.asesor_id = v.id
    GROUP BY v.id
    ORDER BY total_clientes DESC
";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Vendedores</title>
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
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .total {
            font-weight: bold;
            color: #2b502b;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            table {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h2>Reporte de Vendedores y Clientes Asignados</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Vendedor</th>
                <th>Total Clientes</th>
                <?php foreach ($intereses as $i): ?>
                    <th><?= $i ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nombre_completo']) ?></td>
                <td class="total"><?= $row['total_clientes'] ?></td>
                <?php foreach ($intereses as $i): 
                    $alias = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($i));
                    ?>
                    <td><?= $row[$alias] ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>