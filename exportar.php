<?php
include 'bd_conexion.php'; // Conexión a la BD

// Encabezados para exportar como archivo Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ingresos.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Abrir salida para escribir los datos
$salida = fopen('php://output', 'w');

// Escribir la cabecera del archivo Excel
$columnas = ["ID", "Descripción", "Cantidad", "Fecha"];
fputcsv($salida, $columnas, "\t");

// Obtener los datos desde la base de datos
$query = "SELECT * FROM ingresos";
$resultado = $conexion->query($query);

while ($fila = $resultado->fetch_assoc()) {
    fputcsv($salida, $fila, "\t"); // Separador tabular para Excel
}

fclose($salida);
exit();
?>
