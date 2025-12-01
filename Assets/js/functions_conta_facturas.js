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
          <button class="btn btn-danger btn-pdf btn-round ms-auto" 
                  onclick="window.open('${base_url}/Contraseñas/generarContraseña/${row.contraseña}', '_blank')">
            <i class="far fa-file-pdf"></i>
          </button>`;

          return html;
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

  // no pasarse
});
