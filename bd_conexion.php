<?php
// Configuración de conexión
$servidor = "localhost"; 
$usuario = "root";       
$contraseña = "";        
$base_datos = "barmanager"; 

// Crear la conexión
$conexion = new mysqli($servidor, $usuario, $contraseña, $base_datos);

// Comprobar si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

?>
