<?php
// Iniciar sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'bd_conexion.php'; // Conectar a la BD
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Responder a petición AJAX
if (isset($_GET['ajax'])) {
    $query = "SELECT * FROM ingresos";
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $query .= " WHERE Fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }
    $resultado = $conexion->query($query);
    $ingresos = [];
    while ($row = $resultado->fetch_assoc()) {
        $ingresos[] = $row;
    }
    echo json_encode($ingresos);
    exit();
}

// Eliminar ingreso
if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $conexion->real_escape_string($_POST['id']);
    $query = "DELETE FROM ingresos WHERE ID_Ingreso = '$id'";
    echo ($conexion->query($query) === TRUE) ? "success" : "error";
    exit();
}

// Añadir ingreso
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['action'])) {
    if (isset($_POST["descripcion"], $_POST["cantidad"], $_POST["fecha"])) {
        $descripcion = $conexion->real_escape_string($_POST["descripcion"]);
        $cantidad = $conexion->real_escape_string($_POST["cantidad"]);
        $fecha = $conexion->real_escape_string($_POST["fecha"]);

        // Verifica si hay sesión activa
        if (isset($_SESSION['id_admin'])) {
            $id_admin = $_SESSION['id_admin'];
            $query_insert = "INSERT INTO ingresos (Descripcion, Cantidad, Fecha, ID_Admin) 
                             VALUES ('$descripcion', '$cantidad', '$fecha', '$id_admin')";
            echo ($conexion->query($query_insert) === TRUE) ? "success" : "Error al insertar: " . $conexion->error;
        } else {
            echo "No hay sesión activa.";
        }
    } else {
        echo "Faltan datos en el formulario.";
    }
    exit();
}



?>

<!-- Botones añadir y exportar -->
<div class="container-fluid d-flex flex-column">
    <!-- Título de la página -->
    <h5 class="text-secondary d-flex align-items-center fs-6">
        <i class="fa-solid fa-money-bill me-2"></i>Ingresos
    </h5>
    <hr>
    <div class="d-flex flex-wrap justify-content-end">
        <button class="btn btn-primary me-2 mb-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addIngresoModal">
            <i class="fa fa-plus me-2"></i>Añadir
        </button>
        <form action="exportar.php" method="post">
            <input type="hidden" name="type" value="ingresos">
            <button type="submit" class="btn btn-dark mb-2 shadow-sm">
                <i class="fa fa-download me-2"></i>Descargar
            </button>
        </form>

    </div>

    <!-- Filtros de fecha -->
    <div class="row mb-3">
        <div class="col-auto">
            <label for="fechaInicio" class="form-label">Desde:</label>
            <input type="date" class="form-control form-control-sm" id="fechaInicio">
        </div>
        <div class="col-auto">
            <label for="fechaFin" class="form-label">Hasta:</label>
            <input type="date" class="form-control form-control-sm" id="fechaFin">
        </div>
        <div class="col-auto d-flex align-items-end">
            <button class="btn btn-sm btn-dark shadow-sm" id="filtrarIngresos"><i class="fa fa-search"></i></button>
        </div>
    </div>

    <!-- Tabla de Ingresos -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover shadow-sm rounded mt-3">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="ingresosTable">
                <!-- Los ingresos se cargarán aquí mediante JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para añadir ingreso -->
<div class="modal fade" id="addIngresoModal" tabindex="-1" aria-labelledby="addIngresoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header  text-white bg-primary">
                <h5 class="modal-title" id="addIngresoModalLabel">Añadir Ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addIngresoForm" method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" step="0.01" class="form-control" id="cantidad" name="cantidad" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary shadow-sm">Guardar</button>
                    <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>