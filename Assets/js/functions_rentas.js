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
        title: "Acciones",
        render: function (data, type, row) {
          switch (solicitud_estado) {
            case "Validado":
            case "Descartado":
            case "Pagado":
              return `
              <button type="button"
                class="btn btn-secondary m-0 d-flex justify-content-left btnFacturaInfo"
                data-bs-toggle="modal"
                data-bs-target="#infoModal">
                <i class="fas fa-edit"></i>
              </button>
            `;

            case "Pendiente":
              return `
              <button type="button"
                  class="btn btn-primary m-0 d-flex justify-content-left btnFacturaEditar"
                  data-bs-toggle="modal"
                  data-bs-target="#editarModal"
                  data-id="${row.id_detalle}">
                  <i class="fas fa-edit"></i>
                </button>
            `;

            default:
              return `
                <button type="button"
                  class="btn btn-secondary m-0 d-flex justify-content-left btnFacturaInfo"
                  data-bs-toggle="modal"
                  data-bs-target="#infoModal">
                  <i class="fas fa-edit"></i>
                </button>
              `;
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

        $("#mes_renta").val(response.data.mes_renta);

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

$(document).on("click", ".btnPDF", function () {
  const contraseña = $(this).data("id");

  $.ajax({
    url: `${base_url}/SolicitudFondos/getPDF/${contraseña}`,
    method: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status) {
        // Campos base
        $("#mes_renta").val(response.data.mes_renta);

        // Crédito
        if (parseInt(response.data.credito) === 1) {
          toggleCredito(true);

          $("#no_factura").val(response.data.no_factura);
          $("#monto_credito").val(response.data.monto_credito);
        } else {
          toggleCredito(false);
        }

        // Mostrar modal
        $("#pdfRentasModal").modal("show");
      } else {
        alert(response.msg);
      }
    },
    error: function (error) {
      console.log("Error:", error);
    },
  });
});

function toggleCredito(activo) {
  const checkbox = $("#habilitarExtras");
  const inputs = $(".extra-input");

  checkbox.prop("checked", activo);

  inputs.prop("disabled", !activo);

  if (!activo) {
    inputs.val("");
  }
}

$("#habilitarExtras").on("change", function () {
  toggleCredito(this.checked);
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

document.addEventListener("submit", function (event) {
  if (event.target && event.target.id === "GenerarPDF") {
    event.preventDefault();

    let formData = new FormData(event.target);

    let checkbox = document.getElementById("habilitarExtras");

    formData.append("tiene_nota_credito", checkbox.checked ? "1" : "0");

    let ajaxUrl = base_url + "/SolicitudFondos/updateRentaMes";
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
          }).then(() => {
            let contraseña = formData.get("contraseña");

            let pdfUrl =
              base_url +
              "/SolicitudFondos/generarSolicitudRentas/" +
              contraseña;

            window.open(pdfUrl, "_blank");
            location.reload();
          });
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
