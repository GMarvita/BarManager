<!-- Sidebar -->
<div class="d-flex flex-column vh-100 p-3 bg-light text-dark shadow-sm sidebar" aria-label="Sidebar de navegación">
    
    <div class="mb-4 text-center d-flex align-items-center justify-content-center gap-2">
       <!-- Logo a la izquierda -->
       <img src="img/icono.png" alt="Logo" style="height: 40px; width: 40px;">
    <h3 class="text-dark fw-bold fs-4 mb-0">BarManager</h3>
</div>
<hr class="border-light">


    <!-- Sección de Dashboard -->
    <div class="mb-4">
        <h5 class="text-secondary d-flex align-items-center fs-6">
            <i class="fa fa-tachometer-alt me-3"></i> Dashboard
        </h5>
        <ul class="nav flex-column ms-2">
            <li class="nav-item">
                <a href="dashboard.php?page=home" class="nav-link text-dark py-2 fs-6">
                    <i class="fa fa-home me-2"></i> Home
                </a>
            </li>
        </ul>
    </div>

    <!-- Sección de Navigation -->
    <div>
    <h5 class="text-secondary d-flex align-items-center fs-6">
        <i class="fa fa-bars me-3"></i> Navigation
    </h5>
    <ul class="nav flex-column ms-2">
        <li class="nav-item">
            <a href="dashboard.php?page=ingresos" class="nav-link text-dark py-2 fs-6">
                <i class="fa-solid fa-money-bill me-2"></i> Ingresos
            </a>
        </li>
        <li class="nav-item">
            <a href="dashboard.php?page=gastos" class="nav-link text-dark py-2 fs-6">
                <i class="fa fa-money-check-alt me-2"></i> Gastos
            </a>
        </li>
        <li class="nav-item">
            <a href="dashboard.php?page=categorias" class="nav-link text-dark py-2 fs-7">
                <i class="fa fa-th-list me-2"></i> Categoría de gastos
            </a>
        </li>
    </ul>
</div>


    <!-- Sección de Logout -->
    <hr class="border-light">
    <div class="mt-auto">
        <a href="logout.php" class="nav-link text-dark py-2 fs-6">
            <i class="fa fa-sign-out me-2"></i> Cerrar sesión
        </a>
    </div>
</div>
