let divLoading = document.querySelector("#divLoading");

function baseDataTableConfig(url) {
  return {
    ajax: {
      url: url,
      dataSrc: function (json) {
        if (!json.status) {
          Swal.fire({
            icon: "info",
            title: "Sin registros",
            text: json.msg,
          });
          return [];
        }
        return json.data;
      },
    },
    autoWidth: false,
    colReorder: true,
    dom: "Bfrtip",
    buttons: [
      {
        extend: "colvis",
        text: '<i class="fas fa-eye me-1"></i> Columnas',
        className: "btn btn-primary btn-sm me-1 rounded fw-bold text-white",
      },
      {
        extend: "excelHtml5",
        text: '<i class="fas fa-file-excel me-1"></i> Excel',
        className: "btn btn-success btn-sm me-1 rounded fw-bold text-white",
        exportOptions: {
          columns: ":visible", // solo columnas visibles
          modifier: {
            search: "applied", // solo filas filtradas
            order: "applied", // respeta el orden actual
            page: "all", // todas las filas filtradas, no solo la página
          },
        },
      },
    ],
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },
  };
}

function initFacturas(contraseña) {
  $("#tableFacturas").DataTable({
    ...baseDataTableConfig(
      base_url + "/SolicitudFondos/getFacturas/" + contraseña
    ),
    columns: [
      { data: null, render: (d, t, r, m) => m.row + 1, title: "#" },
      { data: "no_factura", title: "No. Factura" },
      { data: "no_comparativa", title: "No. Comparativa", visible: false },
      { data: "no_oc", title: "No. O.C.", visible: false },
      { data: "registro_ax", title: "Registro AX", visible: false },
      { data: "bien_servicio", title: "Bien/Servicio" },
      { data: "valor_documento", title: "Valor Doc." },
      { data: "base", title: "Base" },
      { data: "iva", title: "IVA" },
      { data: "reten_iva", title: "Ret. IVA" },
      { data: "reten_isr", title: "Ret. ISR" },
      { data: "total", title: "Total" },
    ],
  });
}

function initServicios(contraseña) {
  fetch(base_url + "/SolicitudFondos/getServicios/" + contraseña)
    .then((response) => response.json())
    .then((json) => {
      if (!json.status || json.data.length === 0) {
        Swal.fire("Sin registros", json.msg || "No hay datos", "info");
        return;
      }

      const data = json.data;
      let maxMateriales = 0;

      data.forEach((row) => {
        if (row.materiales) {
          const total = row.materiales.split(";").length;
          if (total > maxMateriales) {
            maxMateriales = total;
          }
        }
      });

      let columns = [
        { data: null, title: "#", render: (d, t, r, m) => m.row + 1 },
        { data: "no_factura", title: "No. Factura" },
        { data: "bien_servicio", title: "Bien / Servicio" },
        { data: "valor_documento", title: "Valor Doc." },
        { data: "base", title: "Base" },
        { data: "iva", title: "IVA" },
        { data: "reten_iva", title: "Ret. IVA" },
        { data: "reten_isr", title: "Ret. ISR" },
        { data: "total", title: "Total" },
        { data: "placa", title: "Placa" },
        { data: "usuario", title: "Usuario" },
        { data: "ln", title: "LN" },
      ];

      for (let i = 0; i < maxMateriales; i++) {
        columns.push({
          data: null,
          title: `Material ${i + 1}`,
          render: function (data, type, row) {
            if (!row.materiales) return "";
            const materiales = row.materiales.split(";");
            return materiales[i] ?? "";
          },
        });
      }

      /* ===============================
         5. INICIALIZAR DATATABLE
      =============================== */
      $("#tableServicios").DataTable({
        data: data,
        columns: columns,
        autoWidth: false,
        colReorder: true,
        dom: "Bfrtip",
        buttons: [
          {
            extend: "colvis",
            text: '<i class="fas fa-eye me-1"></i> Columnas',
            className: "btn btn-primary btn-sm me-1 rounded fw-bold text-white",
          },
          {
            extend: "excel",
            text: '<i class="fas fa-file-excel me-1"></i> Excel',
            className: "btn btn-success btn-sm me-1 rounded fw-bold text-white",
            filename: function () {
              return `servicios_${document.querySelector("#contraseña").value}`;
            },
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
    });
}

function initRentas(contraseña) {
  fetch(base_url + "/SolicitudFondos/getRentas/" + contraseña)
    .then((response) => response.json())
    .then((json) => {
      if (!json.status || json.data.length === 0) {
        Swal.fire("Sin registros", json.msg || "No hay datos", "info");
        return;
      }

      const data = json.data;
      let maxMateriales = 0;

      data.forEach((row) => {
        if (row.arrendamientos) {
          const total = row.arrendamientos.split(";").length;
          if (total > maxMateriales) {
            maxMateriales = total;
          }
        }
      });

      let columns = [
        { data: null, title: "#", render: (d, t, r, m) => m.row + 1 },
        { data: "no_factura", title: "No. Factura" },
        { data: "bien_servicio", title: "Bien / Servicio" },
        { data: "valor_documento", title: "Valor Doc." },
        { data: "base", title: "Base" },
        { data: "iva", title: "IVA" },
        { data: "reten_iva", title: "Ret. IVA" },
        { data: "reten_isr", title: "Ret. ISR" },
        { data: "total", title: "Total" },
      ];

      for (let i = 0; i < maxMateriales; i++) {
        columns.push({
          data: null,
          title: `Vehiculo ${i + 1}`,
          render: function (data, type, row) {
            if (!row.arrendamientos) return "";
            const materiales = row.arrendamientos.split(";");
            return materiales[i] ?? "";
          },
        });
      }

      $("#tableRentas").DataTable({
        data: data,
        columns: columns,
        autoWidth: false,
        colReorder: true,
        dom: "Bfrtip",
        buttons: [
          {
            extend: "colvis",
            text: '<i class="fas fa-eye me-1"></i> Columnas',
            className: "btn btn-primary btn-sm me-1 rounded fw-bold text-white",
          },
          {
            extend: "excel",
            text: '<i class="fas fa-file-excel me-1"></i> Excel',
            className: "btn btn-success btn-sm me-1 rounded fw-bold text-white",
            filename: function () {
              return `rentas_${document.querySelector("#contraseña").value}`;
            },
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
    });
}

document.addEventListener("DOMContentLoaded", function () {
  const contraseña = document.querySelector("#contraseña").value;

  if ($("#tableFacturas").length) initFacturas(contraseña);
  if ($("#tableServicios").length) initServicios(contraseña);
  if ($("#tableRentas").length) initRentas(contraseña);
});

$(document).on("click", ".btnFacturaEditar", function () {
  const factura = $(this).data("id");

  $.ajax({
    url: `${base_url}/SolicitudFondos/getFacturaId/${factura}`,
    method: "GET",
    dataType: "json",
    success: function (response) {
      if (!response.status) {
        Swal.fire("Error", response.msg, "error");
        return;
      }

      $("#edit_id").val(response.data.id_detalle);
      $("#edit_id_regimen").val(response.data.id_regimen);
      $("#edit_regimen").val(response.data.nombre_regimen);
      $("#edit_factura").val(response.data.no_factura);
      $("#edit_codax").val(response.data.registro_ax);
      $("#edit_servicio").val(response.data.bien_servicio);
      $("#edit_documento").val(response.data.valor_documento);

      $("#input_iva").val(response.data.iva_valor);
      $("#check_iva").prop("checked", response.data.iva_valor > 0);
      $("#input_iva").prop("disabled", !(response.data.iva_valor > 0));

      $("#input_isr").val(response.data.isr_valor);
      $("#check_isr").prop("checked", response.data.isr_valor > 0);
      $("#input_isr").prop("disabled", !(response.data.isr_valor > 0));

      $("#edit_reten_iva").val(response.data.reten_iva);
      $("#edit_base").val(response.data.base);
      $("#edit_base_iva").val(response.data.iva);
      $("#edit_observacion").val(response.data.observacion);
      $("#edit_fecha_registro").val(response.data.fecha_registro);

      calcular();
    },
  });
});

document.addEventListener("submit", function (event) {
  if (event.target.id !== "DetalleEdit") return;

  event.preventDefault();
  let formData = new FormData(event.target);

  fetch(base_url + "/SolicitudFondos/updateDetalle", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((res) => {
      if (res.status) {
        Swal.fire("Guardado", "Datos actualizados", "success").then(() =>
          location.reload()
        );
      } else {
        Swal.fire("Error", res.msg || "Error desconocido", "error");
      }
    });
});

document.addEventListener("submit", function (event) {
  if (event.target.id !== "validarSolicitud") return;

  event.preventDefault();
  let boton = event.submitter;
  let formData = new FormData(event.target);
  formData.append("respuesta", boton.dataset.respuesta);

  fetch(base_url + "/SolicitudFondos/validarSolicitud", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((res) => {
      if (res.status) {
        Swal.fire("Correcto", "Solicitud validada", "success").then(() =>
          location.reload()
        );
      } else {
        Swal.fire("Error", res.msg, "error");
      }
    });
});

document.addEventListener("submit", function (event) {
  if (event.target.id !== "finalizarSolicitud") return;

  event.preventDefault();
  let boton = event.submitter;

  Swal.fire({
    title: "¿Finalizar solicitud?",
    text: "No podrá realizar cambios después.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, finalizar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (!result.isConfirmed) return;

    let formData = new FormData(event.target);
    formData.append("respuesta", boton.dataset.respuesta);

    fetch(base_url + "/SolicitudFondos/finalizarSolicitud", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((res) => {
        if (res.status) {
          Swal.fire("Finalizado", res.message, "success").then(() =>
            location.reload()
          );
        } else {
          Swal.fire("Error", res.message, "error");
        }
      });
  });
});
