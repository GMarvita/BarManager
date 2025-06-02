<?php
session_start();
include 'bd_conexion.php'; 
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = isset($_POST['email']) ? trim($_POST['email']) : '';
    $contraseña = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($usuario) && !empty($contraseña)) {
        // Buscar solo por email
        $query = "SELECT * FROM administrador WHERE Email = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $user = $resultado->fetch_assoc();
            // Verificar la contraseña con password_verify
            if (password_verify($contraseña, $user['Contraseña'])) {
                $_SESSION['email'] = $user['Email'];
                $_SESSION['id_admin'] = $user['ID_Admin'];
                $_SESSION['nombre'] = $user['Nombre'];

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
        $stmt->close();
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>


<div class="container-login w-100">
    <div class="row justify-content-center w-100 mx-0">
        <div class="col-11 col-sm-8 col-md-4 col-lg-3">
            <div class="card card-login">
                <div class="text-center mb-3">
                    <h3>Iniciar sesión</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label"><i class="fa fa-user me-2"></i>Usuario</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Introduce tu usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"><i class="fa fa-lock me-2"></i>Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Introduce tu contraseña" required>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="#">¿Olvidaste tu contraseña?</a>
                            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>