<!-- sidebar.php -->
<aside class="sidebar">
  <div class="sidebar-header">
    <img src="../assets/img/logo.png" alt="Logo" class="sidebar-logo">
    <h3>LA FINCA DE CARHUAZ</h3>
  </div>
  <nav class="sidebar-nav">
      <ul>
        <li><a href="panel_admin.php"><span>📋</span> Panel principal</a></li>
        <li><a href="clientes.php"><span>👥</span> Clientes</a></li>
        <li><a href="registrar_vendedor.php"><span>➕</span> Vendedores</a></li>
        <li><a href="reporte_vendedores.php"><span>📊</span> Reporte Vendedores</a></li>
        <li><a href="clientes_intereses.php"><span>📈</span> Reporte Grafico</a></li>
        <li><a href="logout.php" onclick="return confirm('¿Deseas cerrar sesión?')"><span>🚪</span> Cerrar sesión</a></li>
      </ul>
  </nav>
</aside>

<style>
/* ===== Sidebar Blanco ===== */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 240px;
  height: 100vh;
  background-color: #ffffff;
  color: #333;
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
  padding: 20px 0;
  font-family: 'Segoe UI', sans-serif;
  z-index: 1000;
}

/* ===== Header de Sidebar ===== */
.sidebar-header {
  text-align: center;
  padding: 10px 20px;
  border-bottom: 1px solid #ddd;
  margin-bottom: 30px;
}

.sidebar-logo {
  width: 60px;
  height: 60px;
  object-fit: contain;
  margin-bottom: 10px;
}

.sidebar-header h3 {
  font-size: 18px;
  margin: 0;
  font-weight: normal;
  color: #3E6C3E;
}

/* ===== Navegación ===== */
.sidebar-nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.sidebar-nav li {
  margin-bottom: 20px; /* separación entre enlaces */
}

.sidebar-nav a {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 20px;
  text-decoration: none;
  color: #3E6C3E;
  font-size: 15px;
  font-weight: 500;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.sidebar-nav a:hover {
  background-color: #f0f5f0;
  color: #2b502b;
  font-weight: bold;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.sidebar-nav a span {
  font-size: 18px;
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
  .sidebar {
    position: relative;
    width: 100%;
    height: auto;
    box-shadow: none;
    border-bottom: 1px solid #ddd;
  }

  .sidebar-nav ul {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
  }

  .sidebar-nav li {
    margin: 0;
  }

  .sidebar-nav a {
    padding: 8px 15px;
    font-size: 14px;
  }

  .sidebar-header {
    display: none;
  }
}
</style>