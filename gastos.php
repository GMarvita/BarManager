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
    $query = "SELECT g.ID_Gasto, c.Nombre AS Categoria, g.Cantidad, g.Fecha 
              FROM gastos g
              LEFT JOIN categorias c ON g.ID_Categoria = c.ID_Categoria";
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $query .= " WHERE g.Fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }
    $resultado = $conexion->query($query);
    $gastos = [];
    while ($row = $resultado->fetch_assoc()) {
        $gastos[] = $row;
    }
    echo json_encode($gastos);
    exit();
}

// Controlar acciones POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = isset($_POST['action']) ? $_POST['action'] : '';

    // Editar gasto
    if ($accion === "update") {
        $id = $_POST["id"];
        $categoria = $_POST["categoria"];
        $cantidad = $_POST["cantidad"];
        $fecha = $_POST["fecha"];

        $stmt = $conexion->prepare("UPDATE gastos SET ID_Categoria = ?, Cantidad = ?, Fecha = ? WHERE ID_Gasto = ?");
        $stmt->bind_param("sdsi", $categoria, $cantidad, $fecha, $id);

        echo $stmt->execute() ? "success" : "error";
        exit();
    }

    // Eliminar gasto
    if ($accion === "delete") {
        $id = $conexion->real_escape_string($_POST['id']);
        $query = "DELETE FROM gastos WHERE ID_Gasto = '$id'";
        echo ($conexion->query($query) === TRUE) ? "success" : "error";
        exit();
    }

    // Añadir gasto (no se espera action)
    if ($accion === '') {
        if (isset($_POST["categoria"], $_POST["cantidad"], $_POST["fecha"])) {
            $categoria = $conexion->real_escape_string($_POST["categoria"]);
            $cantidad = $conexion->real_escape_string($_POST["cantidad"]);
            $fecha = $conexion->real_escape_string($_POST["fecha"]);

            if (isset($_SESSION['id_admin'])) {
                $id_admin = $_SESSION['id_admin'];
                $query_insert = "INSERT INTO gastos (Fecha, Cantidad, ID_Admin, ID_Categoria) 
                                 VALUES ('$fecha', '$cantidad', '$id_admin', '$categoria')";
                echo ($conexion->query($query_insert) === TRUE) ? "success" : "Error al insertar: " . $conexion->error;
            } else {
                echo "No hay sesión activa.";
            }
        } else {
            echo "Faltan datos en el formulario.";
        }
        exit();
    }

    // Acción no válida
    echo "Acción no válida o faltan datos.";
    exit();
}


?>

<!-- Botones añadir y exportar -->
<div class="container-fluid d-flex flex-column">
    <!-- Título de la página -->
    <h5 class="text-secondary d-flex align-items-center fs-6">
        <i class="fa fa-money-check-alt me-2"></i> Gastos
    </h5>
    <hr>
    <div class="d-flex flex-wrap justify-content-end">
        <button class="btn btn-primary me-2 mb-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addGastoModal">
            <i class="fa fa-plus me-2"></i>Añadir
        </button>
        <form action="exportar.php" method="post">
            <input type="hidden" name="type" value="gastos">
            <button type="submit" class="btn btn-dark mb-2 shadow-sm">
                <i class="fa fa-download me-2"></i>Descargar
            </button>
        </form>
    </div>

    <!-- Filtros de fecha -->
    <div class="row mb-3">
        <div class="col-auto">
            <label for="fechaInicioGastos" class="form-label">Desde:</label>
            <input type="date" class="form-control form-control-sm" id="fechaInicioGastos">
        </div>
        <div class="col-auto">
            <label for="fechaFinGastos" class="form-label">Hasta:</label>
            <input type="date" class="form-control form-control-sm" id="fechaFinGastos" name=fecha_fin>
        </div>
        <div class="col-auto d-flex align-items-end">
            <button class="btn btn-sm btn-dark shadow-sm" id="filtrarGastos"><i class="fa fa-search"></i></button>
        </div>
    </div>

    <!-- Tabla de Gastos -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover shadow-sm rounded mt-3">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Categoria</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="gastosTable">
                <!-- Los gastos se cargarán aquí mediante JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para añadir gasto -->
<div class="modal fade" id="addGastoModal" tabindex="-1" aria-labelledby="addGastoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white bg-primary">
                <h5 class="modal-title" id="addGastoModalLabel">Añadir Gasto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="addGastoForm" method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria</label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="" disabled selected>Selecciona una categoría</option> <!-- Opción por defecto -->

                            <?php
                            // Consultar las categorías disponibles
                            $query_categorias = "SELECT ID_Categoria, Nombre FROM categorias";
                            $resultado_categorias = $conexion->query($query_categorias);
                            while ($categoria = $resultado_categorias->fetch_assoc()) {
                                // Solo mostrar las categorías sin marcar ninguna como seleccionada
                                echo "<option value='" . $categoria['ID_Categoria'] . "'>" . $categoria['Nombre'] . "</option>";
                            }
                            ?>
                        </select>

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

<!-- Modal para editar gasto -->
<div class="modal fade" id="editGastoModal" tabindex="-1" aria-labelledby="editGastoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-white bg-primary">
        <h5 class="modal-title" id="editGastoModalLabel">Editar Gasto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="editGastoForm">
        <div class="modal-body">
          <input type="hidden" id="editID_Gasto" name="id">

          <div class="mb-3">
            <label for="editCategoria" class="form-label">Categoría</label>
            <select class="form-select" id="editCategoria" name="categoria" required>
              <option value="" disabled>Selecciona una categoría</option>
              <?php
              // Reutilizar las categorías
              $resultado_categorias->data_seek(0); // Reiniciar el puntero
              while ($categoria = $resultado_categorias->fetch_assoc()) {
                echo "<option value='" . $categoria['ID_Categoria'] . "'>" . $categoria['Nombre'] . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="editCantidad" class="form-label">Cantidad</label>
            <input type="number" step="0.01" class="form-control" id="editCantidad" name="cantidad" required>
          </div>
          <div class="mb-3">
            <label for="editFecha" class="form-label">Fecha</label>
            <input type="date" class="form-control" id="editFecha" name="fecha" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary shadow-sm">Guardar </button>
          <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </form>
    </div>
  </div>
</div>
