<?php
include 'bd_conexion.php';

// Iniciar sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['id_admin'];
$mensaje = "";

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $nueva_contrasena = trim($_POST['nueva_contrasena']);

    if ($nueva_contrasena !== '') {
        // Actualizar con nueva contraseña hasheada
        $hashed_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $sql_update = "UPDATE administrador SET Nombre = ?, Email = ?, Contraseña = ? WHERE ID_Admin = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("sssi", $nombre, $email, $hashed_password, $id_usuario);
    } else {
        // Actualizar sin modificar contraseña
        $sql_update = "UPDATE administrador SET Nombre = ?, Email = ? WHERE ID_Admin = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("ssi", $nombre, $email, $id_usuario);
    }

    if ($stmt_update->execute()) {
        // Actualizar la variable de sesión para reflejar el nuevo nombre
        $_SESSION['nombre'] = $nombre;

        $mensaje = "Datos actualizados correctamente.";
    } else {
        $mensaje = "Error al actualizar los datos.";
    }

    // Responder solo con el mensaje si es AJAX
    if (
        !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
    ) {
        echo $mensaje;
        exit();
    }
}

// Obtener datos del usuario para mostrar el formulario
$sql = "SELECT ID_Admin, Nombre, Email FROM administrador WHERE ID_Admin = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
} else {
    echo "Error al obtener los datos del usuario.";
    exit();
}
?>

<main class="main-content p-4">
    <div class="container">
        <h2 class="text-center text-black mb-5">
            <i class="fa fa-user-shield me-2" aria-hidden="true"></i> Perfil del Administrador
        </h2>

        <div class="card shadow-lg rounded-4 p-4 bg-white border-0 mx-auto" style="max-width: 600px;">
            <form action="perfil.php" method="POST" id="perfilForm">
                <div class="mb-3">
                    <label class="form-label fw-semibold">ID de Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-id-badge"></i></span>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['ID_Admin']); ?>" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($usuario['Nombre']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($usuario['Email']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nueva Contraseña <span class="text-muted">(opcional)</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" id="nueva_contrasena" class="form-control" name="nueva_contrasena" placeholder="••••••••">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1" aria-label="Mostrar contraseña">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa fa-save me-2"></i>Actualizar Perfil
                    </button>
                </div>

                <!-- Aquí mostramos el mensaje -->
                <div id="mensajePerfil" class="alert alert-info text-center mt-3" style="display:none;"></div>
            </form>
        </div>
    </div>
</main>

