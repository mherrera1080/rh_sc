let tableReporte;

document.addEventListener("DOMContentLoaded", function () {
  inicializarTabla(); // primera carga

  // Cambiar tabla seleccionada
  document.getElementById("tipo_tabla").addEventListener("change", function () {
    tableReporte.destroy(); // destruir tabla actual
    inicializarTabla(); // volver a crear con nuevas columnas
  });

  // Botón filtrar
  document.getElementById("btnFiltrar").addEventListener("click", function () {
    tableReporte.ajax.reload();
  });

  // Botón limpiar filtros
  document.getElementById("btnReset").addEventListener("click", function () {
    [
      "f_inicio",
      "f_fin",
      "f_transaccion",
      "estado",
      "area",
      "proveedor",
    ].forEach((id) => (document.getElementById(id).value = ""));

    tableReporte.ajax.reload();
  });
});

function inicializarTabla() {
  let tabla = document.getElementById("tipo_tabla").value;
  let config = obtenerConfiguracion(tabla);

  // 1) Construir thead dinámico
  let thead = "<thead><tr>";
  config.columnas.forEach((col) => {
    thead += `<th>${col.title ?? ""}</th>`;
  });
  thead += "</tr></thead>";

  // 2) Insertarlo dentro de la tabla
  document.querySelector("#tableReporte").innerHTML = thead + "<tbody></tbody>";

  // 3) Ahora sí inicializar DataTable
  tableReporte = $("#tableReporte").DataTable({
    ajax: {
      url: config.url,
      data: function (d) {
        d.f_inicio = document.getElementById("f_inicio").value;
        d.f_fin = document.getElementById("f_fin").value;
        d.f_transaccion = document.getElementById("f_transaccion").value;
        d.estado = document.getElementById("estado").value;
        d.area = document.getElementById("area").value;
        d.proveedor = document.getElementById("proveedor").value;
      },
      dataSrc: function (json) {
        if (!json.status) {
          Swal.fire("Sin registros", json.msg, "info");
          return [];
        }
        return json.data;
      },
    },

    columns: config.columnas,
    dom: "Bfrtip",
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
}

function obtenerConfiguracion(tabla) {
  switch (tabla) {
    case "contraseñas":
      return {
        url: base_url + "/Dashboard/registroContra",
        columnas: [
          {
            data: null,
            title: "#",
            render: function (data, type, row, meta) {
              return meta.row + 1; // contador
            },
          },
          { data: "contraseña", title: "Contraseña" },
          { data: "area", title: "Área" },
          { data: "fecha_registro", title: "Registro" },
          { data: "proveedor", title: "Proveedor" },
          { data: "monto_total", title: "Total" },
          { data: "fecha_pago", title: "Fecha Pago" },
          {
            title: "Estado",
            data: "estado",
            className: "text-center",
            render: function (data, type, row, meta) {
              let html = "";
              data = data.toLowerCase();
              if (data.includes("pendiente")) {
                html = '<span class="badge badge-warning"> ' + data + "</span>";
              } else if (data.includes("validado")) {
                html = '<span class="badge badge-success">VALIDADO</span>';
              } else if (data.includes("pagado")) {
                html = '<span class="badge badge-info">PAGADO</span>';
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
            title: "Observaciones",
            data: "correcciones",
            visible: false,
          },
        ],
      };

    case "solicitudes":
      return {
        url: base_url + "/Dashboard/getSolucitudesFondosConta",
        columnas: [
          {
            data: null,
            title: "#",
            render: function (data, type, row, meta) {
              // Mostrar el número de ítem (índice + 1)
              return meta.row + 1;
            },
          },
          { title: "Contraseña", data: "contraseña" },
          {
            title: "Categoria",
            data: "categoria",
          },
          {
            data: null,
            title: "Pago",
            className: "text-center",
            render: function (data, type, row) {
              if (row.categoria === "Anticipo") {
                return row.fecha_pago_sf ?? "—";
              } else {
                return row.fecha_pago ?? "—";
              }
            },
          },
          { title: "Area", data: "area" },
          { title: "No. Transferencia", data: "no_transferencia" },
          { title: "Fecha Transaccion", data: "fecha_transaccion" },
          {
            title: "Estado",
            data: "estado",
            className: "text-center",
            render: function (data, type, row, meta) {
              let html = "";
              data = data.toLowerCase();
              if (data.includes("pendiente")) {
                html = '<span class="badge badge-warning"> ' + data + "</span>";
              } else if (data.includes("validado")) {
                html = '<span class="badge badge-success">VALIDADO</span>';
              } else if (data.includes("pagado")) {
                html = '<span class="badge badge-info">PAGADO</span>';
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
            title: "Observacion",
            data: "observacion",
            visible: false,
          },
        ],
      };

    case "facturas":
      return {
        url: base_url + "/Dashboard/getDetalles",
        columnas: [
          {
            title: "#",
            data: null,
            render: function (data, type, row, meta) {
              // Mostrar el número de ítem (índice + 1)
              return meta.row + 1;
            },
          },
          { title: "No. Factura", data: "no_factura" },
          { title: "Contraseña", data: "contraseña" },
          { title: "Area", data: "area" },
          { title: "Servicio", data: "bien_servicio" },
          { title: "Valor", data: "valor_documento" },
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
        ],
      };

    case "anticipos":
      return {
        url: base_url + "/Dashboard/getAnticipos",
        columnas: [
          {
            data: null,
            title: "#",
            render: function (data, type, row, meta) {
              // Mostrar el número de ítem (índice + 1)
              return meta.row + 1;
            },
          },
          { title: "Contraseña", data: "contraseña" },
          {
            title: "Categoria",
            data: "categoria",
          },
          {
            data: null,
            title: "Pago",
            className: "text-center",
            render: function (data, type, row) {
              if (row.categoria === "Anticipo") {
                return row.fecha_pago_sf ?? "—";
              } else {
                return row.fecha_pago ?? "—";
              }
            },
          },
          { title: "Area", data: "area" },
          { title: "No. Transferencia", data: "no_transferencia" },
          { title: "Fecha Transaccion", data: "fecha_transaccion" },
          {
            title: "Estado",
            data: "estado",
            className: "text-center",
            render: function (data, type, row, meta) {
              let html = "";
              data = data.toLowerCase();
              if (data.includes("pendiente")) {
                html = '<span class="badge badge-warning"> ' + data + "</span>";
              } else if (data.includes("validado")) {
                html = '<span class="badge badge-success">VALIDADO</span>';
              } else if (data.includes("pagado")) {
                html = '<span class="badge badge-info">PAGADO</span>';
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
            title: "Observacion",
            data: "observacion",
            visible: false,
          },
        ],
      };
  }
}
