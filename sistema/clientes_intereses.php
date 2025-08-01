<?php
require_once 'bd/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Datos para gráfico 1: intereses por rango de edad
$sql1 = "
    SELECT 
        CASE 
            WHEN edad BETWEEN 18 AND 30 THEN '18-30'
            WHEN edad BETWEEN 31 AND 45 THEN '31-45'
            WHEN edad > 45 THEN '46+'
            ELSE 'Sin edad'
        END AS rango_edad,
        interes,
        COUNT(*) AS cantidad
    FROM clientes
    GROUP BY rango_edad, interes
";
$res1 = $conexion->query($sql1);
$datosEdad = [];
while ($row = $res1->fetch_assoc()) {
    $datosEdad[$row['rango_edad']][$row['interes']] = $row['cantidad'];
}
$interesesUnicos = [
    "Proyecto Las Dunas De Carhuaz",
    "Proyecto La Planicie De Costa Sur",
    "Casa Vivienda Girasol",
    "Casa Vivienda Rosas",
    "Casa Vivienda Esmeralda"
];

// Datos para gráfico 2: intereses por departamento
$sql2 = "
    SELECT departamento, COUNT(*) as total 
    FROM clientes 
    GROUP BY departamento
";
$res2 = $conexion->query($sql2);
$datosDepto = [];
while ($row = $res2->fetch_assoc()) {
    $datosDepto[$row['departamento']] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Intereses</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
        }
        .main-content {
            margin-left: 240px; /* Ajuste para dejar espacio al sidebar */
            padding: 30px;
        }
        h2 {
            color: #294129;
            text-align: center;
        }
        .graficos {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
            padding-top: 20px;
        }
        .grafico-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
        }
        canvas {
            margin-top: 20px;
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
    <h2>Reporte de Intereses de Clientes</h2>

    <div class="graficos">
        <!-- Gráfico de intereses por rango de edad -->
        <div class="grafico-box">
            <h3>Intereses por Edad</h3>
            <canvas id="graficoEdad"></canvas>
        </div>

        <!-- Gráfico de interés por departamento -->
        <div class="grafico-box">
            <h3>Clientes por Departamento</h3>
            <canvas id="graficoDepto"></canvas>
        </div>
    </div>
</div>

<script>
const intereses = <?= json_encode($interesesUnicos) ?>;
const datosEdad = <?= json_encode($datosEdad) ?>;
const colores = ['#3E6C3E', '#69A84F', '#BFD8AF'];

const rangos = Object.keys(datosEdad);
const datasetsEdad = rangos.map((r, i) => ({
    label: "Edad " + r,
    backgroundColor: colores[i % colores.length],
    data: intereses.map(interes => datosEdad[r]?.[interes] || 0)
}));

new Chart(document.getElementById('graficoEdad'), {
    type: 'bar',
    data: {
        labels: intereses,
        datasets: datasetsEdad
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: { display: false }
        },
        scales: {
            y: { beginAtZero: true, stepSize: 1 }
        }
    }
});

const datosDepto = <?= json_encode($datosDepto) ?>;
const labelsDepto = Object.keys(datosDepto);
const valoresDepto = Object.values(datosDepto);

new Chart(document.getElementById('graficoDepto'), {
    type: 'bar',
    data: {
        labels: labelsDepto,
        datasets: [{
            label: 'Clientes',
            backgroundColor: '#3E6C3E',
            data: valoresDepto
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { display: false }
        },
        scales: {
            y: { beginAtZero: true, stepSize: 1 }
        }
    }
});
</script>

</body>
</html>
