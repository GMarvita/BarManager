<?php
include 'bd_conexion.php'; // Conectar a la BD

// Verificar si es una solicitud AJAX para devolver JSON
if (isset($_GET['ajax']) && $_GET['ajax'] === 'true') {
  header('Content-Type: application/json'); // Asegurar que la respuesta sea JSON

  $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
  $datosIngresos = [];
  $datosGastos = [];

  // Consultar ingresos por mes
  for ($i = 1; $i <= 12; $i++) {
    $sqlIngresos = "SELECT SUM(cantidad) AS total FROM ingresos WHERE MONTH(fecha) = $i";
    $resultadoIngresos = $conexion->query($sqlIngresos);
    $filaIngresos = $resultadoIngresos->fetch_assoc();
    $datosIngresos[] = $filaIngresos['total'] ?? 0;
  }

  // Consultar gastos por mes
  for ($i = 1; $i <= 12; $i++) {
    $sqlGastos = "SELECT SUM(cantidad) AS total FROM gastos WHERE MONTH(fecha) = $i";
    $resultadoGastos = $conexion->query($sqlGastos);
    $filaGastos = $resultadoGastos->fetch_assoc();
    $datosGastos[] = $filaGastos['total'] ?? 0;
  }

  $conexion->close();

  // Crear JSON para enviar a JavaScript
  echo json_encode([
    "meses" => $meses,
    "ingresos" => $datosIngresos,
    "gastos" => $datosGastos
  ]);

  exit(); // Finalizar el script inmediatamente después de enviar el JSON
}
?>

<body class="bg-light">
  <div class="container py-2">

    <!-- Tarjetas de resumen -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
      <!-- Tarjeta de Ingresos -->
      <div class="col d-flex">
        <div class="card shadow border-0 bg-white text-dark flex-fill" style="box-shadow: 0 4px 15px rgba(0, 128, 0, 0.2);">
          <div class="card-body d-flex align-items-center">
            <div class="p-3 rounded-circle text-success" style="background-color: rgba(0, 128, 0, 0.2);">
              <i class="fa fa-wallet fs-3"></i>
            </div>
            <div class="ms-3">
              <p class="mb-1">Ingresos de
                <?php
                // Mostrar el mes actual en español usando un arreglo manual
                $meses = [
                  'January' => 'Enero',
                  'February' => 'Febrero',
                  'March' => 'Marzo',
                  'April' => 'Abril',
                  'May' => 'Mayo',
                  'June' => 'Junio',
                  'July' => 'Julio',
                  'August' => 'Agosto',
                  'September' => 'Septiembre',
                  'October' => 'Octubre',
                  'November' => 'Noviembre',
                  'December' => 'Diciembre'
                ];
                $mesActual = date('F');
                echo $meses[$mesActual];
                ?>
              </p>
              <h5 class="fw-bold">
                <?php
                include 'bd_conexion.php'; // Conectar a la BD
                $mesActual = date('m'); // Obtener el mes actual

                // Consulta para obtener la suma de ingresos del mes actual
                $sql = "SELECT SUM(cantidad) AS total_ingresos FROM ingresos WHERE MONTH(fecha) = $mesActual";
                $resultado = $conexion->query($sql);

                if ($resultado) {
                  $fila = $resultado->fetch_assoc();
                  $totalIngresos = $fila['total_ingresos'] ?? 0; // Si no hay ingresos, devuelve 0
                  echo number_format($totalIngresos, 2) . " €";
                } else {
                  echo "Error en la consulta: " . $conexion->error;
                }

                $conexion->close(); // Cerrar conexión
                ?>

              </h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Tarjeta de Gastos -->
      <div class="col d-flex">
        <div class="card shadow border-0 bg-white text-dark flex-fill" style="box-shadow: 0 4px 15px rgba(255, 0, 0, 0.2);">
          <div class="card-body d-flex align-items-center">
            <div class="p-3 rounded-circle text-danger" style="background-color: rgba(255, 0, 0, 0.2);">
              <i class="fa fa-credit-card fs-3"></i>
            </div>
            <div class="ms-3">
              <p class="mb-1">Gastos de
                <?php
                // Mostrar el mes actual en español usando un arreglo manual
                $meses = [
                  'January' => 'Enero',
                  'February' => 'Febrero',
                  'March' => 'Marzo',
                  'April' => 'Abril',
                  'May' => 'Mayo',
                  'June' => 'Junio',
                  'July' => 'Julio',
                  'August' => 'Agosto',
                  'September' => 'Septiembre',
                  'October' => 'Octubre',
                  'November' => 'Noviembre',
                  'December' => 'Diciembre'
                ];
                $mesActual = date('F');
                echo $meses[$mesActual];
                ?> </p>
              <h5 class="fw-bold">
                <?php
                include 'bd_conexion.php'; // Conectar a la BD
                $mesActual = date('m'); // Obtener el mes actual

                // Consulta para obtener la suma de gastos del mes actual
                $sql = "SELECT SUM(cantidad) AS total_gastos FROM gastos WHERE MONTH(fecha) = $mesActual";
                $resultado = $conexion->query($sql);

                if ($resultado) {
                  $fila = $resultado->fetch_assoc();
                  $totalGastos = $fila['total_gastos'] ?? 0; // Si no hay gastos, devuelve 0
                  echo number_format($totalGastos, 2) . " €";
                } else {
                  echo "Error en la consulta: " . $conexion->error;
                }

                $conexion->close(); // Cerrar conexión
                ?>

              </h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Tarjeta de Promedio Mensual -->
      <div class="col d-flex">
        <div class="card shadow border-0 bg-white text-dark flex-fill" style="box-shadow: 0 4px 15px rgba(0, 255, 255, 0.2);">
          <div class="card-body d-flex align-items-center">
            <div class="p-3 rounded-circle text-info" style="background-color: rgba(0, 255, 255, 0.2);">
              <i class="fa fa-chart-line fs-3"></i>
            </div>
            <div class="ms-3">
              <p class="mb-1">Balance <?php
                                      // Mostrar el mes actual en español usando un arreglo manual
                                      $meses = [
                                        'January' => 'Enero',
                                        'February' => 'Febrero',
                                        'March' => 'Marzo',
                                        'April' => 'Abril',
                                        'May' => 'Mayo',
                                        'June' => 'Junio',
                                        'July' => 'Julio',
                                        'August' => 'Agosto',
                                        'September' => 'Septiembre',
                                        'October' => 'Octubre',
                                        'November' => 'Noviembre',
                                        'December' => 'Diciembre'
                                      ];
                                      $mesActual = date('F');
                                      echo $meses[$mesActual];
                                      ?></p>
              <h5 class="fw-bold">
                <?php
                include 'bd_conexion.php'; // Conectar a la BD
                $mesActual = date('m'); // Obtener el mes actual

                // Consulta para obtener la suma de ingresos del mes actual
                $sqlIngresos = "SELECT SUM(cantidad) AS total_ingresos FROM ingresos WHERE MONTH(fecha) = $mesActual";
                $resultadoIngresos = $conexion->query($sqlIngresos);

                // Consulta para obtener la suma de gastos del mes actual
                $sqlGastos = "SELECT SUM(cantidad) AS total_gastos FROM gastos WHERE MONTH(fecha) = $mesActual";
                $resultadoGastos = $conexion->query($sqlGastos);

                // Obtener valores o asignar 0 si no hay datos
                $totalIngresos = ($resultadoIngresos) ? $resultadoIngresos->fetch_assoc()['total_ingresos'] ?? 0 : 0;
                $totalGastos = ($resultadoGastos) ? $resultadoGastos->fetch_assoc()['total_gastos'] ?? 0 : 0;

                // Calcular balance
                $balance = $totalIngresos - $totalGastos;

                // Mostrar balance formateado
                echo number_format($balance, 2) . " €";

                // Cerrar conexión
                $conexion->close();
                ?>

              </h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Tarjeta de Perfil -->
      <div class="col d-flex">
        <div class="card shadow border-0 bg-white text-dark flex-fill" style="box-shadow: 0 4px 15px rgba(0, 0, 255, 0.2);">
          <div class="card-body d-flex align-items-center">
            <div class="p-3 rounded-circle text-primary" style="background-color: rgba(0, 0, 255, 0.2);">
              <i class="fa fa-user fs-3"></i>
            </div>
            <div class="ms-3">
              <p class="mb-1">Perfil</p>
              <h5 class="fw-bold">
                <?php
                // Mostrar el nombre del usuario desde la sesión si está disponible
                if (isset($_SESSION['nombre']) && !empty($_SESSION['nombre'])) {
                  echo htmlspecialchars($_SESSION['nombre']); // Mostrar el nombre del usuario
                } else {
                  echo "Usuario"; // Valor predeterminado en caso de que no haya sesión
                }
                ?>
              </h5>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- Gráficos -->
    <div class="row mt-4">
      <div class="col-md-6 d-flex">
        <div class="card shadow-sm border-0 flex-fill">
          <div class="card-body">
            <h6 class="fw-bold mb-3">Ingresos Mensuales</h6>
            <canvas id="ingresosChart" style="height: 350px;"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6 d-flex">
        <div class="card shadow-sm border-0 flex-fill">
          <div class="card-body">
            <h6 class="fw-bold mb-3">Gastos Mensuales</h6>
            <canvas id="gastosChart" style="height: 350px;"></canvas> <!-- Cambiado a <canvas> -->
          </div>
        </div>
      </div>


    </div>

    <div class="row mt-4">
      <div class="col-12 d-flex">
        <div class="card shadow-sm border-0 flex-fill">
          <div class="card-body p-3">
            <h6 class="fw-bold mb-3">Balance de Ingresos y Gastos</h6>
            <canvas id="balanceChart" style="height: 300px; width: 100%;""></canvas> <!-- Reducir altura -->
          </div>
        </div>
      </div>
    </div>
  </div>
</body>