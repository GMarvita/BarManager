<header class="header bg-light text-black shadow-sm">
    <div class="menu-icons ms-auto d-flex align-items-center ">
        
        <!-- Mostrar la inicial del usuario en un círculo -->
        <a href="#" class="text-black">
            <?php
            // Mostrar la inicial del nombre del usuario en mayúsculas si está presente
            if (isset($_SESSION['nombre']) && !empty($_SESSION['nombre'])) {
                // Obtener la primera letra del nombre en mayúscula
                $initial = strtoupper(substr($_SESSION['nombre'], 0, 1)); 
                echo "<span class='user-initial'>$initial</span>";
            } else {
                echo "<span class='user-initial'>U</span>"; // Valor predeterminado en caso de que no haya sesión
            }
            ?>
        </a>
    </div>
</header>
