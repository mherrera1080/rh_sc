let tableSolicitudes;

document.addEventListener("DOMContentLoaded", function () {
  // Tabla principal con datos AJAX
  tableSolicitudes = $("#tableSolicitudes").DataTable({
    ajax: {
      url: base_url + "/SolicitudFondos/getSolucitudesFondosVehiculos",
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
      { data: "contraseña" },
      {
        data: "categoria",
      },
      {
        data: null, // usamos null porque tomaremos varios campos del row
        className: "text-center",
        render: function (data, type, row) {
          if (row.categoria === "Combustible") {
            return row.fecha_pago_sf ?? "—";
          } else {
            return row.fecha_pago ?? "—";
          }
        },
      },
      { data: "area" },
      { data: "no_transferencia" },
      { data: "fecha_transaccion" },
      {
        data: "estado",
        className: "text-center",
        render: function (data, type, row, meta) {
          let html = "";
          data = data.toLowerCase();
          if (data.includes("pendiente")) {
            html = '<span class="badge badge-warning"> PENDIENTE </span>';
          } else if (data.includes("validado")) {
            html = '<span class="badge badge-success">VALIDADO</span>';
          } else if (data.includes("pagado")) {
            html = '<span class="badge badge-info">PAGADO</span>';
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
        className: "text-center",
        render: function (data, type, row) {
          let html = "";

          if (row.categoria !== "Anticipo") {
            // Si el estado es diferente a Anticipo
            html = `<button class="btn btn-info btn-sm" onclick="window.location.href='${base_url}/SolicitudFondos/${row.categoria}/${row.contraseña}'">
                <i class="fas fa-archive"></i>
              </button>`;
          } else {
            // Si el estado es Anticipo
            html = `<button class="btn btn-info btn-sm" onclick="window.location.href='${base_url}/SolicitudFondos/Anticipo/${row.id_solicitud}'">
                <i class="fas fa-archive"></i>
              </button>`;
          }

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

  $(document).on("click", ".btn-password", function () {
    $.ajax({
      url: `${base_url}/Contraseñas/lastPassword`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#contraseña").val(response.data.nueva_contraseña);
          $("#fecha_registro").val(response.data.fecha_registro);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  if (document.querySelector("#proveedor")) {
    let ajaxUrl = base_url + "/Contraseñas/getSelectProveedor"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#proveedor").innerHTML = request.responseText;
        $("#proveedor");
      }
    };
  }

  const agregarFacturaBtn = document.getElementById("agregarFactura");
  const tablaFacturas = document.getElementById("tablaFacturas");

  agregarFacturaBtn.addEventListener("click", () => {
    // Crear nueva fila
    const nuevaFila = document.createElement("tr");
    nuevaFila.innerHTML = `
      <td><input type="num" class="form-control" name="transferencia[]" required></td>
      <td><input type="num" class="form-control " name="saldo[]" required></td>
      <td><input type="date" class="form-control " name="inicio[]" required></td>
      <td><input type="date" class="form-control " name="final[]" required></td>
    `;

    tablaFacturas.querySelector("tbody").appendChild(nuevaFila);

    agregarFacturaBtn.classList.add("d-none");
  });

  document
    .getElementById("setSolicitud")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      const spinner = document.querySelector("#spinerSubmit");
      const submitButton = document.querySelector("#btnSubmit");

      submitButton.disabled = true;
      spinner.classList.remove("d-none");

      fetch(base_url + "/SolicitudFondos/guardarSolicitudCombustible", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status) {
            Swal.fire({
              title: "Éxito",
              text: data.message,
              icon: "success",
              confirmButtonText: "OK",
            }).then(() => location.reload());
            $("#setContraseñaModal").modal("hide");
            tableSolicitudes.ajax.reload();
          } else {
            Swal.fire({
              title: "Advertencia",
              text: data.message,
              icon: "error",
              confirmButtonText: "Entendido",
            });
          }
          submitButton.disabled = false;
          spinner.classList.add("d-none");
        });
    });

  // no pasarse
});
