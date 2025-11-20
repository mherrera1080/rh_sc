let tableFacturas;
let divLoading = document.querySelector("#divLoading");

document.addEventListener("DOMContentLoaded", function () {
  let contraseña = document.querySelector("#contraseña").value;
  let solicitud_estado = document.querySelector("#solicitud_estado").value;
  let usuario = document.querySelector("#area_usuario").value;

  tableFacturas = $("#tableFacturas").DataTable({
    ajax: {
      url: base_url + "/SolicitudFondos/getFacturas/" + contraseña,
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
      { data: null, render: (d, t, r, m) => m.row + 1, title: "#" },
      { data: "no_factura", title: "No. Factura" },
      { data: "no_comparativa", title: "No. Comparativa", visible: false },
      { data: "no_oc", title: "No. O.C.", visible: false },
      { data: "registro_ax", title: "Registro AX", visible: false },
      { data: "bien_servicio", title: "Bien/Servicio" },
      { data: "valor_documento", title: "Valor Doc." },
      { data: "reten_iva", title: "Ret. IVA" },
      { data: "reten_isr", title: "Ret. ISR" },
      { data: "iva", title: "IVA", visible: false },
      { data: "base", title: "Base" },
      { data: "total", title: "Total" },
      { data: "fecha_registro", title: "Fecha Registro", visible: false },
      {
        data: null,
        render: function (data, type, row) {
          if (
            solicitud_estado != "Descartado" &&
            solicitud_estado != "Pagado" &&
            usuario == 4
          ) {
            return `
          <button type="button" class="btn btn-primary m-0 d-flex justify-content-left btnFacturaEditar"
            data-bs-toggle="modal" data-bs-target="#editarModal" data-id="${row.id_detalle}">
            <i class="fas fa-edit"></i>
          </button>`;
          } else {
            return `
          <button type="button" class="btn btn-secondary m-0 d-flex justify-content-left btnFacturaInfo"
            data-bs-toggle="modal" 
            data-bs-target="#infoModal">
            <i class="fas fa-edit"></i>
          </button>`;
          }
        },
      },
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

// =============================
// 📌 Guardar cambios en detalle
// =============================
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

document.addEventListener("submit", function (event) {
  if (event.target && event.target.id === "validarSolicitud") {
    event.preventDefault();

    // Detecta el botón presionado
    let boton = event.submitter;
    let valor = boton.dataset.respuesta;
    let formData = new FormData(event.target);
    formData.append("respuesta", valor);

    let ajaxUrl = base_url + "/SolicitudFondos/validarSolicitud";
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
  if (event.target && event.target.id === "finalizarSolicitud") {
    event.preventDefault();

    // Detecta el botón presionado
    let boton = event.submitter;
    let valor = boton.dataset.respuesta;
    let formData = new FormData(event.target);
    formData.append("respuesta", valor);

    let ajaxUrl = base_url + "/SolicitudFondos/finalizarSolicitud";
    let request = new XMLHttpRequest();
    request.open("POST", ajaxUrl, true);
    request.send(formData);

    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
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
      }
    };
  }
});
