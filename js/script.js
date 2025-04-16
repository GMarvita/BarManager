$(document).ready(function () {
  cargarIngresos();
  // Filtrar ingresos por fecha
  $("#filtrarIngresos").click(function () {
    let fechaInicio = $("#fechaInicio").val();
    let fechaFin = $("#fechaFin").val();

    if (fechaInicio === "" || fechaFin === "") {
      alert("Por favor, selecciona ambas fechas.");
      return;
    }

    cargarIngresos(fechaInicio, fechaFin);
  });

  // Función para cargar ingresos con o sin filtro
  function cargarIngresos(fechaInicio = "", fechaFin = "") {
    $.ajax({
      url: "ingresos.php",
      method: "GET",
      data: { fecha_inicio: fechaInicio, fecha_fin: fechaFin, ajax: 1 },
      success: function (data) {
        let ingresos = JSON.parse(data);
        let tableBody = $("#ingresosTable");

        tableBody.empty(); // Limpiar la tabla

        if (ingresos.length > 0) {
          ingresos.forEach(ingreso => {
            tableBody.append(`
              <tr data-id="${ingreso.ID_Ingreso}">
                <td>${ingreso.ID_Ingreso}</td>
                <td>${ingreso.Descripcion}</td>
                <td>$${ingreso.Cantidad}</td>
                <td>${ingreso.Fecha}</td>
                <td>
                  <button class="btn btn-warning btn-sm editarIngreso"><i class="fa fa-pencil-alt"></i></button>
                  <button class="btn btn-danger btn-sm eliminarIngreso" data-id="${ingreso.ID_Ingreso}"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            `);
          });
        } else {
          tableBody.append("<tr><td colspan='5' class='text-center'>No hay ingresos disponibles.</td></tr>");
        }
      },
      error: function () {
        alert("Error al cargar los ingresos.");
      }
    });
  }

  // Eliminar ingreso
  $(document).on("click", ".eliminarIngreso", function () {
    let id = $(this).data("id");

    if (confirm("¿Estás seguro de eliminar este ingreso?")) {
      $.ajax({
        url: "ingresos.php",
        method: "POST",
        data: { action: "delete", id: id },
        success: function (response) {
          if (response.trim() === "success") {
            alert("Ingreso eliminado correctamente.");
            cargarIngresos(); // Recargar la tabla después de eliminar
          } else {
            alert("Error al eliminar el ingreso.");
          }
        }
      });
    }
  });

// Enviar el formulario de añadir ingreso
$("#addIngresoForm").submit(function (e) {
    e.preventDefault(); // Evita el envío tradicional del formulario

    $.ajax({
        type: "POST",
        url: "ingresos.php",
        data: $(this).serialize(),
        xhrFields: {
            withCredentials: true
        },
        success: function (response) {
            let res;
            try {
                res = JSON.parse(response);
            } catch (e) {
                alert("Respuesta inesperada del servidor");
                console.log(response);
                return;
            }

            if (res.status === "success") {
                alert("Ingreso guardado correctamente");
                $("#addIngresoModal").modal('hide'); // Cierra el modal después de guardar
                cargarIngresos(); // Recarga los ingresos en la tabla
            } else {
                alert("Error al guardar el ingreso: " + (res.message || ""));
                console.log(res);
            }
        }
    });
});


});



$(document).ready(function () {
  cargarGastos();
  // Filtrar ingresos por fecha
  $("#filtrarGastos").click(function () {
    let fechaInicio = $("#fechaInicio").val();
    let fechaFin = $("#fechaFin").val();

    if (fechaInicio === "" || fechaFin === "") {
      alert("Por favor, selecciona ambas fechas.");
      return;
    }

    cargarGastos(fechaInicio, fechaFin);
  });

  // Función para cargar ingresos con o sin filtro
  function cargarGastos(fechaInicio = "", fechaFin = "") {
    $.ajax({
      url: "gastos.php",
      method: "GET",
      data: { fecha_inicio: fechaInicio, fecha_fin: fechaFin, ajax: 1 },
      success: function (data) {
        let gastos = JSON.parse(data);
        let tableBody = $("#gastosTable");

        tableBody.empty(); // Limpiar la tabla

        if (gastos.length > 0) {
          gastos.forEach(gasto => {
            tableBody.append(`
              <tr data-id="${gasto.ID_Gasto}">
                <td>${gasto.ID_Gasto}</td>
                <td>${gasto.Descripcion}</td>
                <td>$${gasto.Cantidad}</td>
                <td>${gasto.Fecha}</td>
                <td>
                  <button class="btn btn-warning btn-sm editarGasto"><i class="fa fa-pencil-alt"></i></button>
                  <button class="btn btn-danger btn-sm eliminarGasto" data-id="${gasto.ID_Gasto}"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            `);
          });
        } else {
          tableBody.append("<tr><td colspan='5' class='text-center'>No hay gastos disponibles.</td></tr>");
        }
      },
      error: function () {
        alert("Error al cargar los gastos.");
      }
    });
  }

  // Eliminar ingreso
  $(document).on("click", ".eliminarGasto", function () {
    let id = $(this).data("id");

    if (confirm("¿Estás seguro de eliminar este gasto?")) {
      $.ajax({
        url: "gastos.php",
        method: "POST",
        data: { action: "delete", id: id },
        success: function (response) {
          if (response.trim() === "success") {
            alert("Gasto eliminado correctamente.");
            cargarGastos(); // Recargar la tabla después de eliminar
          } else {
            alert("Error al eliminar el gasto.");
          }
        }
      });
    }
  });

 // Enviar el formulario de añadir ingreso
 $("#addGastoForm").submit(function (e) {
  e.preventDefault(); // Evita el envío tradicional del formulario

  $.ajax({
      type: "POST",
      url: "gastos.php",
      data: $(this).serialize(),
      success: function (response) {
          if (response === "success") {
              alert("Gasto guardado correctamente");
              $("#addGastoModal").modal('hide'); // Cierra el modal después de guardar
              cargarIngresos(); // Recarga los ingresos en la tabla
          } else {
              alert("Error al guardar el gasto");
          }
      }
  });
});


});


  document.addEventListener("DOMContentLoaded", function () {
    // Obtener datos para los gráficos
    fetch('home.php?ajax=true')
        .then(response => response.json())
        .then(data => {
        

            // Gráfico de Ingresos
            const ctxIngresos = document.getElementById('ingresosChart');
            const ctxGastos = document.getElementById('gastosChart');
            const ctxBalance = document.getElementById('balanceChart'); // Nuevo gráfico de balance

            if (ctxIngresos) {
                new Chart(ctxIngresos, {
                    type: 'bar',
                    data: {
                        labels: data.meses,
                        datasets: [{
                            label: 'Ingresos',
                            data: data.ingresos.map(Number), // Asegurar que son números
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Gráfico de Gastos
            if (ctxGastos) {
                new Chart(ctxGastos, {
                    type: 'bar',
                    data: {
                        labels: data.meses,
                        datasets: [{
                            label: 'Gastos',
                            data: data.gastos.map(Number), // Asegurar que son números
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Gráfico de Balance (Ingresos y Gastos juntos)
            if (ctxBalance) {
                new Chart(ctxBalance, {
                    type: 'bar',
                    data: {
                        labels: data.meses,
                        datasets: [
                            {
                                label: 'Ingresos',
                                data: data.ingresos.map(Number),
                                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Gastos',
                                data: data.gastos.map(Number),
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        })
        .catch(error => console.error('Error al cargar los datos:', error));
});
