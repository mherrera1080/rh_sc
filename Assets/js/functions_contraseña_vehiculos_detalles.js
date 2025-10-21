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
      { data: "bien_servicio" },
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
          if (row.estado === "Pendiente" && usuario == 4) {
            return `
              <button type="button" class="btn btn-primary m-0 d-flex justify-content-left btnFacturaEditar"
                data-bs-toggle="modal" data-bs-target="#editarModal" data-id="${row.id_detalle}">
                <i class="fas fa-edit"></i>
              </button>`;
          } else if (row.estado === "Validado") {
            return `
                        <button type="button" class="btn btn-primary m-0 d-flex justify-content-left btn-info btnFactura"
            data-bs-toggle="modal" data-bs-target="#infoModal">
            <i class="fas fa-info-circle"></i>
          </button>`;
          }
          return "";
        },
      },
    ],
    dom: "lfrtip", // 👈 Esto habilita búsqueda, paginación y selector de registros
    bDestroy: true,
    iDisplayLength: 5, // cantidad de registros por página
    order: [[0, "desc"]],
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
      url: `${base_url}/SolicitudFondos/getFacturaId/${factura}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#edit_id").val(response.data.id_detalle);
          $("#edit_factura").val(response.data.no_factura);
          $("#edit_servicio").val(response.data.bien_servicio);
          $("#edit_documento").val(response.data.valor_documento);
          $("#edit_fecha_registro").val(response.data.fecha_registro);
          $("#edit_estado").val(response.data.estado);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  document
    .querySelector("#validarForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Detecta el botón presionado
      let boton = event.submitter;
      let valor = boton.dataset.respuesta;

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

      let boton = event.submitter;
      let valor = boton.dataset.respuesta;

      let formData = new FormData(this);
      formData.append("respuesta", valor);

      let ajaxUrl = base_url + "/Vehiculos/solicitudFondos";
      let request = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");

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
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                location.reload();
              }
            });
            $("#solicitarFondosVehiculos").modal("hide");
          } else {
            Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
          }
        }
      };
    });

  document.addEventListener("submit", function (event) {
    if (event.target && event.target.id === "DetalleEdit") {
      event.preventDefault();
      let formData = new FormData(event.target);
      let ajaxUrl = base_url + "/SolicitudFondos/updateDetalle";

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
  //bla bla bla
});

$(document).on("click", ".btnFacturaEditar", function () {
  const factura = $(this).data("id");

  $.ajax({
    url: `${base_url}/SolicitudFondos/getFacturaId/${factura}`,
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

        // IVA
        $("#input_iva").val(response.data.iva_valor);
        if (response.data.iva_valor && response.data.iva_valor > 0) {
          $("#check_iva").prop("checked", true);
          $("#input_iva").prop("disabled", false);
        } else {
          $("#check_iva").prop("checked", false);
          $("#input_iva").prop("disabled", true).val("");
        }

        // ISR
        $("#input_isr").val(response.data.isr_valor);
        if (response.data.isr_valor && response.data.isr_valor > 0) {
          $("#check_isr").prop("checked", true);
          $("#input_isr").prop("disabled", false);
        } else {
          $("#check_isr").prop("checked", false);
          $("#input_isr").prop("disabled", true).val("");
        }

        $("#edit_reten_iva").val(response.data.reten_iva);
        $("#edit_base").val(response.data.base);
        $("#edit_base_iva").val(response.data.iva);
        $("#edit_observacion").val(response.data.observacion);
        $("#edit_fecha_registro").val(response.data.fecha_registro);

        // ⚡ recalcular después de cargar la data
        calcular();
      } else {
        alert(response.msg);
      }
    },
    error: function (error) {
      console.log("Error:", error);
    },
  });
});
