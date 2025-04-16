<?php
include 'bd_conexion.php'; // Conectar a la BD

$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

if (isset($_GET['ajax'])) {
    $query = "SELECT * FROM gastos";

    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $query .= " WHERE Fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }

    $resultado = $conexion->query($query);

    $gastos = [];
    while ($row = $resultado->fetch_assoc()) {
        $gastos[] = $row;
    }

    echo json_encode($gastos);
    exit();
}

// Eliminar ingreso
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $conexion->real_escape_string($_POST['id']);

    $query = "DELETE FROM gastos WHERE ID_Gasto = '$id'";

    if ($conexion->query($query) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que los datos del formulario estén definidos
    if (isset($_POST["descripcion"], $_POST["cantidad"], $_POST["fecha"])) {
        // Escapar los datos del formulario para prevenir inyecciones SQL
        $descripcion = $conexion->real_escape_string($_POST["descripcion"]);
        $cantidad = $conexion->real_escape_string($_POST["cantidad"]);
        $fecha = $conexion->real_escape_string($_POST["fecha"]);
    } else {
        echo "Faltan datos en el formulario.";
        exit();
    }

    // Verificar si ID_Admin está en la sesión
    if (isset($_SESSION['id_admin']) && !empty($_SESSION['id_admin'])) {
        $id_admin = $_SESSION['id_admin']; // Obtener el ID_Admin desde la sesión

        // Mostrar la consulta para depurar
        $query_insert = "INSERT INTO gastos (Descripcion, Cantidad, Fecha, ID_Admin) VALUES ('$descripcion', '$cantidad', '$fecha', '$id_admin')";
        echo "Consulta SQL: " . $query_insert;  // Verifica la consulta

        // Ejecutar la consulta
        if ($conexion->query($query_insert) === TRUE) {
            echo "Gasto añadido con éxito";
        } else {
            echo "Error en la consulta: " . $conexion->error;  // Muestra el error de la consulta
        }
    } else {
        echo "Error: No hay sesión activa o no se ha encontrado el ID_Admin.";
    }
}
?>

<div class="container-fluid d-flex flex-column ">

    <div class="d-flex flex-wrap justify-content-end">
        <button class="btn btn-primary me-2 mb-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addGastoModal">
            <i class="fa fa-plus me-2"></i>Añadir
        </button>
        <button class="btn btn-dark mb-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addGastoModal">
            <i class="fa fa-download me-2"></i>Exportar
        </button>
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
            <thead class="table-secundary">
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="gastosTable">
                <!-- Los ingresos se cargarán aquí mediante JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para añadir ingreso -->
<div class="modal fade" id="addGastoModal" tabindex="-1" aria-labelledby="addGastoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addGastoModalLabel">Añadir Gasto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addGastoForm">
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
                    <input type="hidden" name="id_admin" value="<?php echo $_SESSION['id_admin']; ?>">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary shadow-sm">Guardar</button>
                    <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>