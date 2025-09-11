let tableSolicitudes;

document.addEventListener("DOMContentLoaded", function () {
  // Tabla principal con datos AJAX
  tableSolicitudes = $("#tableSolicitudes").DataTable({
    ajax: {
      url: base_url + "/SolicitudFondos/getSolucitudesFondos",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { data: "contraseña" },
      { data: "area" },
      { data: "categoria" },
      { data: "fecha_creacion" },
      {
        data: "contraseña",
        render: function (data, type, row) {
          html = `<button class="btn btn-info btn-sm" onclick="window.location.href='${base_url}/SolicitudFondos/Revision/${data}'">
                      <i class="fas fa-archive"></i>
                    </button>`;

          return html;
        },
      },
    ],
    dom: "lfrtip",
    bDestroy: true,
    iDisplayLength: 5,
    order: [[0, "desc"]],
  });

  // no pasarse
});
