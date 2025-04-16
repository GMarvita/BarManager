<?php
// Incluir archivo de conexión a la base de datos
include 'bd_conexion.php';

// Consulta para obtener las categorías (ajusta según tu base de datos)
$consulta = "SELECT * FROM categorias";  // Suponiendo que tienes una tabla 'categorias'
$resultado = mysqli_query($conexion, $query);

// Verificar si se obtuvieron resultados
$categorias = [];
if ($resultado) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $categorias[] = $fila;
    }
} else {
    echo "Error al obtener las categorías: " . mysqli_error($conexion);
}

?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Lista de Categorías</h1>
        
        <!-- Tabla para mostrar las categorías -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?php echo $categoria['id']; ?></td>
                        <td><?php echo htmlspecialchars($categoria['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($categoria['descripcion']); ?></td>
                        <td>
                            <a href="editar_categoria.php?id=<?php echo $categoria['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar_categoria.php?id=<?php echo $categoria['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Botón para agregar una nueva categoría -->
        <div class="text-center mt-4">
            <a href="agregar_categoria.php" class="btn btn-primary">Agregar Categoría</a>
        </div>
    </div>
<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
