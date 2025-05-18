<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

include 'bd_conexion.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : ''; // Acción dentro de vacations
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="css/styles.css">

    <!-- Cargar jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Luego los scripts que dependen de jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Script de Bootstrap para los modales y otros componentes interactivos -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/script.js"></script>
</head>

<body class="dashboard-page d-flex vh-100">

    <!-- Sidebar -->
    <div class="sidebar">
        <?php include 'header.php'; ?>
        <?php include 'leftpanel.php'; ?>
    </div>

    <!-- Main Content -->
    <main id="page-content-wrapper" class="container-fluid flex-grow-1 d-flex flex-column bg-white">
        <div class="content-container  p-3 bg-light">
            <?php
            if ($page == 'home') {
                include 'home.php';
            } elseif ($page == 'ingresos') {
                include 'ingresos.php';
            } elseif ($page == 'gastos') {
                include 'gastos.php';
            } elseif ($page == 'categorias') {
                include 'categorias.php';
            }
            elseif ($page == 'perfil') {
                include 'perfil.php';
            }
            ?>
        </div>
    </main>



</body>

</html>