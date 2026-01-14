let tableFacturas;
let divLoading = document.querySelector("#divLoading");

document.addEventListener("DOMContentLoaded", function () {
  let contraseña = document.querySelector("#contraseña").value;
  let usuario = document.querySelector("#usuario").value;

  tableFacturas = $("#tableFacturas").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    autoWidth: false,
    language: {
      url: base_url + "/Assets/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Contraseñas/getFacturas/" + contraseña,
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
    columnDefs: [
      {
        targets: 0,
        width: "40",
        className: "text-center",
      },
    ],
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },
      { data: "no_factura" },
      { data: "valor_documento" },
      {
        data: "estado",
        title: "Estado",
        render: function (data, type, row, meta) {
          let html = "";
          data = data.toLowerCase();
          if (data.includes("pendiente")) {
            html = '<span class="badge badge-warning">' + data + "</span>";
          } else if (data.includes("validado")) {
            html = '<span class="badge badge-success">VALIDADO</span>';
          } else if (data.includes("corregir")) {
            html = '<span class="badge badge-danger">CORREGIR</span>';
          } else if (data.includes("descartado")) {
            html = '<span class="badge badge-danger">DESCARTADO</span>';
          }
          return html;
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          return `
              <button type="button" class="btn btn-primary m-0 d-flex justify-content-left btnFacturaEditar"
            data-bs-toggle="modal" data-bs-target="#editarModal" data-id="${row.id_detalle}">
            <i class="fas fa-edit"></i>
          </button>
            `;
        },
      },
    ],
    dom: "lfrtip", // 👈 Esto habilita búsqueda, paginación y selector de registros
    bDestroy: true,
    iDisplayLength: 5, // cantidad de registros por página
    order: [[0, "desc"]],
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },
  });

  $(document).on("input", ".factura", function () {
    this.value = this.value.replace(/\D/g, ""); // Solo dígitos
  });

  // Validar números decimales (acepta punto o coma)
  $(document).on("input", ".valor", function () {
    this.value = this.value
      .replace(/[^0-9.,]/g, "") // Quitar caracteres no válidos
      .replace(/(,|\.){2,}/g, "$1") // Evita múltiples puntos/comas seguidos
      .replace(/^(\.|,)/g, ""); // No permitir punto/coma al principio
  });

  $(document).on("click", ".btnFacturaEditar", function () {
    const factura = $(this).data("id");

    $.ajax({
      url: `${base_url}/Contabilidad/getFactura/${factura}`,
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (!response.status) {
          alert(response.msg);
          return;
        }

        const data = response.data;

        $("#edit_id").val(data.id_detalle);
        $("#edit_factura").val(data.no_factura);
        $("#edit_documento").val(data.valor_documento);
        $("#edit_servicio").val(data.bien_servicio);
        $("#edit_regimen").val(data.nombre_regimen);

        $("#edit_iva").val(data.iva);
        $("#edit_reten_iva").val(data.reten_iva);
        $("#edit_reten_isr").val(data.reten_isr);
        $("#edit_total").val(data.total_liquido);
        $("#edit_base").val(data.base);
      },
      error: function (xhr) {
        console.error(xhr.responseText);
      },
    });
  });

  document
    .querySelector("#validarForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      /* 1. Validar botón presionado */
      if (!event.submitter) {
        Swal.fire("Atención", "Acción no válida.", "warning");
        return;
      }

      let boton = event.submitter;
      let valor = boton.dataset.respuesta;

      /* 2. Validar data-respuesta */
      if (!valor) {
        Swal.fire("Atención", "Respuesta no definida.", "warning");
        return;
      }

      /* 3. Validar campos required del formulario */
      const camposRequeridos = this.querySelectorAll("[required]");
      for (let campo of camposRequeridos) {
        if (!campo.value.trim()) {
          Swal.fire(
            "Atención",
            "Debe completar todos los campos obligatorios.",
            "warning"
          );
          campo.focus();
          return;
        }
      }

      /* 4. Validación condicional (ejemplo: rechazo requiere motivo) */
      if (valor === "RECHAZADO") {
        let motivo = this.querySelector("#motivo");
        if (motivo && !motivo.value.trim()) {
          Swal.fire(
            "Atención",
            "Debe indicar el motivo del rechazo.",
            "warning"
          );
          motivo.focus();
          return;
        }
      }

      /* 5. Envío AJAX */
      let formData = new FormData(this);
      formData.append("respuesta", valor);

      let ajaxUrl = base_url + "/Vehiculos/validarContraseña";
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
    });

  document
    .querySelector("#solicitudVehiculosForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      /* 1. Validar botón presionado */
      if (!event.submitter) {
        Swal.fire("Atención", "Acción no válida.", "warning");
        return;
      }

      const boton = event.submitter;
      const respuesta = boton.dataset.respuesta;

      if (!respuesta) {
        Swal.fire("Atención", "Respuesta no definida.", "warning");
        return;
      }

      /* 2. Validación condicional de categoría */
      const categoria = this.querySelector("#categoria");

      if (respuesta === "Validado") {
        if (!categoria || !categoria.value) {
          Swal.fire(
            "Atención",
            "Debe seleccionar una categoría para validar la solicitud.",
            "warning"
          );
          categoria.focus();
          return;
        }
      }

      /* 3. Confirmación */
      Swal.fire({
        title:
          respuesta === "Descartado"
            ? "¿Desea descartar esta solicitud?"
            : "¿Desea validar esta solicitud de fondos?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",
        reverseButtons: true,
      }).then((result) => {
        if (!result.isConfirmed) return;

        /* 4. Envío AJAX */
        const formData = new FormData(event.target);
        formData.append("respuesta", respuesta);

        const ajaxUrl = base_url + "/Vehiculos/solicitudFondos";
        const request = new XMLHttpRequest();
        request.open("POST", ajaxUrl, true);
        request.send(formData);

        request.onreadystatechange = function () {
          if (request.readyState !== 4) return;

          try {
            if (request.status === 200) {
              const response = JSON.parse(request.responseText);

              if (response.status) {
                Swal.fire({
                  title: response.message || "Proceso realizado correctamente",
                  icon: "success",
                  confirmButtonText: "Aceptar",
                }).then(() => location.reload());

                $("#solicitarFondosVehiculos").modal("hide");
              } else {
                Swal.fire(
                  "Atención",
                  response.message || "Error al procesar la solicitud.",
                  "error"
                );
              }
            } else {
              Swal.fire(
                "Error",
                "Hubo un problema al procesar la solicitud. Código: " +
                  request.status,
                "error"
              );
            }
          } catch (e) {
            console.error(
              "Error al procesar la respuesta:",
              e,
              request.responseText
            );
            Swal.fire("Error", "Respuesta inesperada del servidor.", "error");
          }
        };
      });
    });

  //bla bla bla
});
