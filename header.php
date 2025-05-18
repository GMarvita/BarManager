//Header
<header class="header bg-light text-black shadow-sm px-3 py-2">
    <div class="menu-icons ms-auto d-flex align-items-center position-relative">
        <div class="dropdown">
            <a href="#" class="dropdown-toggle d-flex align-items-center text-decoration-none m-2" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?php
                
                if (isset($_SESSION['nombre']) && !empty($_SESSION['nombre'])) {
                    $initial = strtoupper(substr($_SESSION['nombre'], 0, 1));
                    echo "<span class='user-initial'>$initial</span>";
                } else {
                    echo "<span class='user-initial'>U</span>";
                }
                ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow m-2" aria-labelledby="userDropdown">
                <li><a class="dropdown-item text-black" href="dashboard.php?page=perfil"><i class="fa fa-user me-2"></i>Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-black" href="logout.php"><i class="fa fa-sign-out me-2"></i>Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</heade