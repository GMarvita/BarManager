<?php
include('bd_conexion.php'); // Asegúrate de incluir tu archivo de conexión a la base de datos

// Comprobar si la solicitud es una petición AJAX para obtener categorías
if (isset($_GET['ajax'])) {
    // Consultar todas las categorías
    $query = "SELECT * FROM categorias"; // Ajusta el nombre de la tabla si es diferente
    $resultado = $conexion->query($query);

    $categorias = [];
    while ($row = $resultado->fetch_assoc()) {
        $categorias[] = $row;
    }

    // Devolver los datos como JSON
    echo json_encode($categorias);
    exit();
}

// Comprobar si la solicitud es para agregar una nueva categoría
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['action'])) {
    $nombre = $_POST['nombre']; // Asegúrate de sanitizar los datos de entrada para evitar inyecciones SQL

    if (!empty($nombre)) {
        // Insertar nueva categoría
        $query = "INSERT INTO categorias (Nombre) VALUES ('$nombre')";
        if ($conexion->query($query)) {
            echo "success"; // Indicar que la categoría se ha insertado correctamente
        } else {
            echo "error"; // Si ocurre un error en la inserción
        }
    } else {
        echo "error"; // Si el nombre está vacío
    }
    exit();
}
// Comprobar si la solicitud es para editar una categoría
if (isset($_POST['action']) && $_POST['action'] == 'edit' && isset($_POST['id']) && isset($_POST['nombre'])) {
    $id = intval($_POST['id']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);

    $query = "UPDATE categorias SET Nombre = '$nombre' WHERE ID_Categoria = $id";
    if ($conexion->query($query)) {
        echo "success";
    } else {
        echo "error";
    }
    exit();
}

// Comprobar si la solicitud es para eliminar una categoría
if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Eliminar la categoría con el ID proporcionado
    $query = "DELETE FROM categorias WHERE ID_Categoria = $id";
    if ($conexion->query($query)) {
        echo "success"; // Indicar que la categoría se ha eliminado correctamente
    } else {
        echo "error"; // Si ocurre un error en la eliminación
    }
    exit();
}
?>


<!-- Botones añadir y exportar -->
<div class="container-fluid d-flex flex-column">
    <!-- Título de la página -->
    <h5 class="text-secondary d-flex align-items-center fs-6">
        <i class="fa fa-th-list me-2"></i> Categoria de gastos
    </h5>
    <hr>
    <div class="d-flex flex-wrap justify-content-end">
        <button class="btn btn-primary me-2 mb-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoriaModal">
            <i class="fa fa-plus me-2"></i>Añadir
        </button>
    </div>

    <!-- Tabla de Categorías -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover shadow-sm rounded mt-3">
            <thead class="table-primary">
                <tr>

                    <th>Categorías</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="categoriasTable">
                <!-- Las categorías se cargarán aquí mediante JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para añadir/editar categoría -->
<div class="modal fade" id="addCategoriaModal" tabindex="-1" aria-labelledby="addCategoriaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-white bg-primary">
        <h5 class="modal-title" id="addCategoriaModalLabel">Añadir Categoría</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addCategoriaForm" method="POST">
        <div class="modal-body">
          <input type="hidden" name="id_categoria" id="id_categoria"> <!-- Campo oculto -->
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Categoría</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
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
