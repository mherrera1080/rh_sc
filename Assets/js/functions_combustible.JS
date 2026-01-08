let tableFacturas;
let divLoading = document.querySelector("#divLoading");

document.addEventListener("DOMContentLoaded", function () {
  let contraseña = document.querySelector("#contraseña").value;

  tableFacturas = $("#tableFacturas").DataTable({
    ajax: {
      url: base_url + "/SolicitudFondos/getCombustible/" + contraseña,
      dataSrc: function (json) {
        // Si no hay datos, muestra swal y evita error
        if (!json.status) {
          Swal.fire({
            icon: "info",
            title: "Sin registros",
            text: json.msg,
          });
          return []; // Retornar arreglo vacío para que DataTables no falle
        }
        return json.data;
      },
    },
    autoWidth: false,
    colReorder: true,
    columns: [
      { data: "transferencia", title: "TRANSFERENCIA PARA COMBUSTIBLE" },
      { data: "saldo_disponible", title: "SALDO DISPONIBLE" },
      { data: "rango_fechas", title: "RANGO FECHA" },

    ],
    dom: "Bfrtip",
    buttons: [
      {
        extend: "colvis",
        text: '<i class="fas fa-eye me-1"></i> Columnas',
        className: "btn btn-primary btn-sm me-1 rounded fw-bold text-white",
        collectionLayout: "fixed two-column",
        postfixButtons: ["colvisRestore"],
      },
      {
        extend: "excel",
        text: '<i class="fas fa-file-excel me-1"></i> Excel',
        className: "btn btn-success btn-sm me-1 rounded fw-bold text-white",
      },
      {
        extend: "print",
        text: '<i class="fas fa-print me-1"></i> Imprimir',
        className: "btn btn-secondary btn-sm rounded fw-bold text-white",
      },
    ],
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },
  });

  // no borrar
});

$(document).on("click", ".btnFacturaEditar", function () {
  const factura = $(this).data("id");

  $.ajax({
    url: `${base_url}/SolicitudFondos/getFacturaRenta/${factura}`,
    method: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status) {
        // Rellenar campos
        $("#edit_id").val(response.data.id_detalle);
        $("#edit_id_regimen").val(response.data.id_regimen);
        $("#edit_regimen").val(response.data.nombre_regimen);

        $("#edit_factura").val(response.data.no_factura);
        $("#edit_codax").val(response.data.registro_ax);
        $("#edit_servicio").val(response.data.bien_servicio);
        $("#edit_documento").val(response.data.valor_documento);

        $("#edit_base").val(response.data.base);
        $("#edit_base_iva").val(response.data.iva);
        $("#edit_observacion").val(response.data.observacion);

        const arrendamientos = response.data.arrendamientos_array;

        // Reiniciar contenedor
        resetContenedorMateriales();

        // Agregar los materiales existentes
        arrendamientos.forEach((m) => {
          agregarInputMaterial(m);
        });
      } else {
        alert(response.msg);
      }
    },
    error: function (error) {
      console.log("Error:", error);
    },
  });
});

document.addEventListener("submit", function (event) {
  if (event.target && event.target.id === "ArrendamientoEdit") {
    event.preventDefault();
    let formData = new FormData(event.target);
    let ajaxUrl = base_url + "/SolicitudFondos/updateDetalleRenta";

    let request = new XMLHttpRequest();
    request.open("POST", ajaxUrl, true);
    request.send(formData);

    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        let response = JSON.parse(request.responseText);
        if (response.status) {
          Swal.fire({
            title: "Datos guardados correctamente",
            icon: "success",
            confirmButtonText: "Aceptar",
          }).then(() => location.reload());
        } else {
          Swal.fire("Atención", response.msg || "Error desconocido", "error");
        }
      }
    };
  }
});

document.addEventListener("submit", function (event) {
  if (event.target && event.target.id === "validarSolicitud") {
    event.preventDefault();

    const form = event.target;
    const boton = event.submitter;

    // Validación: botón presionado
    if (!boton || !boton.dataset.respuesta) {
      Swal.fire("Atención", "Acción no válida.", "warning");
      return;
    }

    // Validación: campos obligatorios (ejemplo)
    const observacion = form.querySelector("[name='observacion']");
    if (observacion && observacion.value.trim() === "") {
      Swal.fire("Atención", "Debe ingresar una observación.", "warning");
      return;
    }

    // Evitar doble envío
    boton.disabled = true;

    let formData = new FormData(form);
    formData.append("respuesta", boton.dataset.respuesta);

    let ajaxUrl = base_url + "/SolicitudFondos/validarSolicitud";
    let request = new XMLHttpRequest();
    request.open("POST", ajaxUrl, true);
    request.send(formData);

    request.onreadystatechange = function () {
      if (request.readyState === 4) {
        boton.disabled = false;

        if (request.status === 200) {
          let response = JSON.parse(request.responseText);

          if (response.status) {
            Swal.fire({
              title: "Datos guardados correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then(() => location.reload());
          } else {
            Swal.fire("Atención", response.msg || "Error desconocido", "error");
          }
        } else {
          Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
        }
      }
    };
  }
});


document.addEventListener("submit", function (event) {
  if (event.target && event.target.id === "finalizarSolicitud") {
    event.preventDefault();

    const form = event.target;
    const boton = event.submitter;

    // Validación: botón presionado
    if (!boton || !boton.dataset.respuesta) {
      Swal.fire("Atención", "Acción no válida.", "warning");
      return;
    }

    // Confirmación antes de finalizar
    Swal.fire({
      title: "¿Desea finalizar la solicitud?",
      text: "Esta acción no se puede revertir.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, finalizar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (!result.isConfirmed) return;

      boton.disabled = true;

      let formData = new FormData(form);
      formData.append("respuesta", boton.dataset.respuesta);

      let ajaxUrl = base_url + "/SolicitudFondos/finalizarSolicitudSinContra";
      let request = new XMLHttpRequest();
      request.open("POST", ajaxUrl, true);
      request.send(formData);

      request.onreadystatechange = function () {
        if (request.readyState === 4) {
          boton.disabled = false;

          if (request.status === 200) {
            let response = JSON.parse(request.responseText);

            if (response.status) {
              Swal.fire({
                title: response.message,
                icon: "success",
                confirmButtonText: "Aceptar",
              }).then(() => location.reload());
            } else {
              Swal.fire(
                "Atención",
                response.message || "Error desconocido",
                "error"
              );
            }
          } else {
            Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
          }
        }
      };
    });
  }
});

