let tableFacturas;

document.addEventListener("DOMContentLoaded", function () {
  // Tabla principal con datos AJAX
  tableFacturas = $("#tableFacturas").DataTable({
    ajax: {
      url: base_url + "/Contabilidad/getDetalles",
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
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { data: "no_factura" },
      { data: "contraseña" },
      { data: "area" },
      { data: "bien_servicio" },
      { data: "valor_documento" },
      { data: "total" },
      { data: "registro_ax" },
      {
        data: "estado",
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
          } else if (data.includes("pagado")) {
            html = '<span class="badge badge-info">PAGADO</span>';
          }
          return html;
        },
      },
      {
        data: "estado",
        render: function (data, type, row) {
          html = `
          <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editFactura" data-id="${row.id_detalle}">
            <i class="fa-solid fa-arrows-rotate"></i>
          </button>

          <button class="btn btn-danger btn-pdf" 
                  onclick="window.open('${base_url}/Contraseñas/generarContraseña/${row.contraseña}', '_blank')">
            <i class="far fa-file-pdf"></i>
          </button>
          `;
          return html;
        },
      },
    ],
    dom: "Blfrtip",
    bDestroy: true,
    iDisplayLength: 5, // cantidad de registros por página
    order: [[0, "desc"]],
    buttons: [
      {
        extend: "colvis",
        text: '<i class="fas fa-eye me-1"></i> Columnas',
        className: "btn btn-primary btn-sm me-1 rounded fw-bold text-white",
        collectionLayout: "fixed two-column",
        postfixButtons: ["colvisRestore"],
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
  });

  $(document).on("click", ".edit-btn", function () {
    const id = $(this).data("id");

    // Limpia el formulario antes de cargar nuevos datos
    $("#formEditFactura")[0].reset();

    $.ajax({
      url: `${base_url}/Contabilidad/getDetalle/${id}`,
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          $("#id_detalle").val(data.id_detalle);
          $("#no_factura").val(data.no_factura);
          $("#registro_ax").val(data.registro_ax);
          $("#bien_servicio").val(data.bien_servicio);
          $("#base").val(data.base);
          $("#reten_iva").val(data.reten_iva);
          $("#reten_isr").val(data.reten_isr);
          $("#valor_documento").val(data.valor_documento);
          $("#observacion").val(data.observacion);
          $("#area").val(data.area);
        } else {
          Swal.fire({
            title: "Error",
            text: response.msg,
            icon: "error",
            confirmButtonText: "Aceptar",
          });
        }
      },
      error: function (error) {
        console.error("Error:", error);
        Swal.fire(
          "Error",
          "No se pudo obtener la información del detalle.",
          "error",
        );
      },
    });
  });

  document.addEventListener("submit", function (event) {
    if (event.target && event.target.id === "formEditFactura") {
      event.preventDefault();
      Swal.fire({
        title: "¿Está seguro de finalizar la cambiar los datos?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, finalizar",
        cancelButtonText: "Cancelar",
        reverseButtons: true,
      }).then((result) => {
        if (!result.isConfirmed) {
          return;
        }
        let formData = new FormData(event.target);
        let ajaxUrl = base_url + "/Contabilidad/setFactura";
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
                "error",
              );
            }
          }
        };
      });
    }
  });

  // no pasarse
});
