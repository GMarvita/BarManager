<?php
include 'bd_conexion.php';

$type = isset($_POST['type']) ? $_POST['type'] : 'ingresos';
$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
$fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename={$type}.xls");
header("Pragma: no-cache");
header("Expires: 0");

$salida = fopen('php://output', 'w');

if ($type === 'ingresos') {
    $columnas = ["ID", "Descripcion", "Cantidad", "Fecha"];
    fputcsv($salida, $columnas, "\t");

    $sql = "SELECT ID_Ingreso, Descripcion, Cantidad, Fecha FROM ingresos";

    if ($fecha_inicio !== '' && $fecha_fin !== '') {
        $sql .= " WHERE Fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }

    $resultado = $conexion->query($sql);

    $total = 0;
    while ($fila = $resultado->fetch_assoc()) {
        fputcsv($salida, $fila, "\t");
        $total += floatval($fila['Cantidad']);
    }

    fputcsv($salida, ['', 'TOTAL', $total, '', ''], "\t");

} elseif ($type === 'gastos') {
    $columnas = ["ID_Gasto", "Categoria", "Cantidad", "Fecha"];
    fputcsv($salida, $columnas, "\t");

    // Consulta con JOIN para traer el nombre de la categoría
    $sql = "SELECT g.ID_Gasto, c.Nombre AS Categoria, g.Cantidad, g.Fecha
            FROM gastos g
            INNER JOIN categorias c ON g.ID_Categoria = c.ID_Categoria";

    if ($fecha_inicio !== '' && $fecha_fin !== '') {
        $sql .= " WHERE g.Fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }

    $resultado = $conexion->query($sql);

    $total = 0;
    while ($fila = $resultado->fetch_assoc()) {
        fputcsv($salida, $fila, "\t");
        $total += floatval($fila['Cantidad']);
    }

    fputcsv($salida, ['', 'TOTAL', $total, ''], "\t");

} else {
    die("Tipo de exportación no válido.");
}

fclose($salida);
exit();
?>
