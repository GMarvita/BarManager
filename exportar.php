<?php
include 'bd_conexion.php'; // Conexión a la BD

// Verificar si se pasó un tipo de exportación (ingresos o gastos) desde el formulario
$type = isset($_POST['type']) ? $_POST['type'] : 'ingresos';

// Definir el nombre del archivo Excel dependiendo del tipo
$filename = $type . ".xls";

// Establecer los encabezados para exportar como archivo Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

// Abrir salida para escribir los datos
$salida = fopen('php://output', 'w');

// Escribir la cabecera del archivo Excel dependiendo del tipo
if ($type === 'ingresos') {
    $columnas = ["ID", "Descripcion", "Cantidad", "Fecha", "ID_Admin"];
    fputcsv($salida, $columnas, "\t"); // Separador tabular para Excel

    // Obtener los datos de ingresos desde la base de datos
    $query = "SELECT * FROM ingresos";
    $resultado = $conexion->query($query);
} elseif ($type === 'gastos') {
    $columnas = ["ID", "Descripcion", "Cantidad", "Fecha", "ID_Admin"];
    fputcsv($salida, $columnas, "\t"); // Separador tabular para Excel

    // Obtener los datos de gastos desde la base de datos
    $query = "SELECT * FROM gastos";
    $resultado = $conexion->query($query);
} else {
    // Si no se pasa un tipo válido, mostrar un mensaje de error
    die("Tipo de exportación no válido.");
}

while ($fila = $resultado->fetch_assoc()) {
    fputcsv($salida, $fila, "\t"); // Separador tabular para Excel
}

fclose($salida);
exit();
?>
