let tableModulos;
document.addEventListener("DOMContentLoaded", function () {
  tableModulos = $("#tableModulos").DataTable({
    ajax: {
      url: base_url + "/Configuracion/getModulos",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { data: "nombre_modulo" },
      { data: "nombre_area" },
      { data: "estado" },
      {
        data: null,
        render: function (data, type, row) {
          return `<button class="btn btn-info btn-sm"">
                    <i class="fas fa-archive"></i>
                  </button>`;
        },
      },
    ],
    dom: "Bfrtip",
  });

  // no pasarse
});
