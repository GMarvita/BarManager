$(document).ready(function () {
  function formatearFecha(fechaISO) {
  const partes = fechaISO.split("-");
  return `${partes[2]}-${partes[1]}-${partes[0]}`; // dd-mm-yyyy
}

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
                <td>${ingreso.Cantidad}€</td>
                <td>${formatearFecha(ingreso.Fecha)}</td>

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
      success: function (response) {
          if (response === "success") {
              alert("Ingreso guardado correctamente");
              $("#addIngresoModal").modal('hide'); // Cierra el modal después de guardar
              cargarIngresos(); // Recarga los ingresos en la tabla
          } else {
              alert("Error al guardar el ingreso");
          }
      }
  });
});

// Limpiar el formulario al cerrar el modal
$('#addIngresoModal').on('hidden.bs.modal', function () {
  $('#addIngresoForm')[0].reset(); // Limpia el formulario
});



});

$(document).ready(function () {
   function formatearFecha(fechaISO) {
  const partes = fechaISO.split("-");
  return `${partes[2]}-${partes[1]}-${partes[0]}`; // dd-mm-yyyy
}
  cargarGastos();

  // Filtrar gastos por fecha
  $("#filtrarGastos").click(function () {
    let fechaInicio = $("#fechaInicioGastos").val();
    let fechaFin = $("#fechaFinGastos").val();

    if (fechaInicio === "" || fechaFin === "") {
      alert("Por favor, selecciona ambas fechas.");
      return;
    }

    cargarGastos(fechaInicio, fechaFin);
  });

  // Función para cargar gastos con o sin filtro
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
                <td>${gasto.Categoria}</td> <!-- Aquí ahora el valor es el nombre de la categoría -->
                <td>${gasto.Cantidad}€</td>
                <td>${formatearFecha(gasto.Fecha)}</td>

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
  

  // Eliminar gasto
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

  // Enviar el formulario de añadir gasto
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
          cargarGastos(); // Recarga los gastos en la tabla
        } else {
          alert("Error al guardar el gasto");
        }
      }
    });
  });

  // Limpiar el formulario al cerrar el modal
  $('#addGastoModal').on('hidden.bs.modal', function () {
    $('#addGastoForm')[0].reset(); // Limpia el formulario
  });

});

$(document).ready(function () {
  // Función para cargar las categorías
  cargarCategorias();

  // Función para cargar las categorías desde la base de datos
  function cargarCategorias() {
    $.ajax({
      url: "categorias.php",
      method: "GET",
      data: { ajax: 1 },
      success: function (data) {
        let categorias = JSON.parse(data);
        let tableBody = $("#categoriasTable");

        tableBody.empty(); // Limpiar la tabla

        if (categorias.length > 0) {
          categorias.forEach(categoria => {
            tableBody.append(`
              <tr data-id="${categoria.ID_Categoria}">
                
                <td>${categoria.Nombre}</td>
                <td>
                  <button class="btn btn-warning btn-sm editarCategoria"><i class="fa fa-pencil-alt"></i></button>
                  <button class="btn btn-danger btn-sm eliminarCategoria" data-id="${categoria.ID_Categoria}"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            `);
          });
        } else {
          tableBody.append("<tr><td colspan='3' class='text-center'>No hay categorías disponibles.</td></tr>");
        }
      },
      error: function () {
        alert("Error al cargar las categorías.");
      }
    });
  }

  // Enviar el formulario de añadir categoría
  $("#addCategoriaForm").submit(function (e) {
    e.preventDefault(); // Evita el envío tradicional del formulario

    $.ajax({
      type: "POST",
      url: "categorias.php",
      data: $(this).serialize(),
      success: function (response) {
        if (response === "success") {
          alert("Categoría guardada correctamente");
          $("#addCategoriaModal").modal('hide'); // Cierra el modal después de guardar
          cargarCategorias(); // Recarga las categorías en la tabla
        } else {
          alert("Error al guardar la categoría");
        }
      }
    });
  });

  // Eliminar categoría
  $(document).on("click", ".eliminarCategoria", function () {
    let id = $(this).data("id");

    if (confirm("¿Estás seguro de eliminar esta categoría?")) {
      $.ajax({
        url: "categorias.php",
        method: "POST",
        data: { action: "delete", id: id },
        success: function (response) {
          if (response.trim() === "success") {
            alert("Categoría eliminada correctamente.");
            cargarCategorias(); // Recargar la tabla después de eliminar
          } else {
            alert("Error al eliminar la categoría.");
          }
        }
      });
    }
  });

  // Limpiar el formulario al cerrar el modal
  $('#addCategoriaModal').on('hidden.bs.modal', function () {
    $('#addCategoriaForm')[0].reset(); // Limpia el formulario
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

// Cambiar el perfil
$(document).ready(function() {
    // Mostrar / ocultar contraseña
    $('#togglePassword').click(function () {
        const input = $('#nueva_contrasena');
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Enviar formulario con AJAX y mostrar mensaje temporal
    $('#perfilForm').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                if ($('#mensajePerfil').length === 0) {
                    $form.before('<div id="mensajePerfil" class="alert alert-info text-center mt-3"></div>');
                }
                $('#mensajePerfil').html(response).fadeIn();

                // Desaparecer mensaje después de 3 segundos
                setTimeout(function() {
                    $('#mensajePerfil').fadeOut();
                }, 1000);
            },
            error: function() {
                if ($('#mensajePerfil').length === 0) {
                    $form.before('<div id="mensajePerfil" class="alert alert-danger text-center mt-3"></div>');
                }
                $('#mensajePerfil').html('Error al actualizar. Intenta nuevamente.').fadeIn();

                setTimeout(function() {
                    $('#mensajePerfil').fadeOut();
                }, 1000);
            }
        });
    });
});