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
      {
        data: "categoria",
        render: function (data, type, row, meta) {
          let html = "";
          data = data.toLowerCase();
          if (data.includes("anticipo")) {
            html = '<span class="badge badge-info"> ANTICIPO </span>';
          } else if (data.includes("factura")) {
            html = '<span class="badge badge-success">FACTURA</span>';
          }
          return html;
        },
      },
      {
        data: null, // usamos null porque tomaremos varios campos del row
        className: "text-center",
        render: function (data, type, row) {
          if (row.categoria === "Anticipo") {
            return row.fecha_pago_sf ?? "—";
          } else {
            return row.fecha_pago ?? "—";
          }
        },
      },
      { data: "area" },
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
          } else if (data.includes("finalizado")) {
            html = '<span class="badge badge-success">FINALIZADO</span>';
          } else if (data.includes("corregir")) {
            html = '<span class="badge badge-danger">CORREGIR</span>';
          } else if (data.includes("anticipo")) {
            html = '<span class="badge badge-info">ANTICIPO</span>';
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
            html = `<button class="btn btn-info btn-sm" onclick="window.location.href='${base_url}/SolicitudFondos/Revision/${row.contraseña}'">
                <i class="fas fa-archive"></i>
              </button>`;
          } else {
            // Si el estado es Anticipo
            html = `<button class="btn btn-info btn-sm" onclick="window.location.href='${base_url}/SolicitudFondos/RevisionSinContra/${row.id_solicitud}'">
                <i class="fas fa-archive"></i>
              </button>`;
          }

          return html;
        },
      },
    ],
    dom: "lfrtip",
    bDestroy: true,
    iDisplayLength: 5,
    order: [[0, "desc"]],
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
      <td><input type="text" class="form-control factura" name="tipo[]¿" value="Anticipo" readonly></td>
      <td><input type="text" class="form-control bien" name="bien[]" required></td>
      <td><input type="text" class="form-control valor" name="valor[]" placeholder="1000.00" required></td>
      <td>
          <button type="button" class="btn btn-danger eliminarFila">
              <i class="fas fa-trash-alt"></i>
          </button>
      </td>
    `;

    // Agregar la fila al cuerpo de la tabla
    tablaFacturas.querySelector("tbody").appendChild(nuevaFila);

    // 🔹 Ocultar o desactivar el botón
    agregarFacturaBtn.classList.add("d-none"); // <-- Oculta completamente
    // agregarFacturaBtn.disabled = true; // <-- O si prefieres solo desactivarlo

    // Configurar el botón de eliminar
    const eliminarBtn = nuevaFila.querySelector(".eliminarFila");
    eliminarBtn.addEventListener("click", () => {
      nuevaFila.remove();
      // 🔹 Reaparecer el botón al eliminar la fila
      agregarFacturaBtn.classList.remove("d-none");
      // agregarFacturaBtn.disabled = false;
    });
  });

  document
    .getElementById("setSolicitud")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this); // Ya incluye todos los input[name="...[]"]

      const spinner = document.querySelector("#spinerSubmit");
      const submitButton = document.querySelector("#btnSubmit");

      submitButton.disabled = true;
      spinner.classList.remove("d-none");

      fetch(base_url + "/SolicitudFondos/guardarSolicitudFondos", {
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
            tableContraseña.ajax.reload();
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
        })
        .catch((error) => {
          Swal.fire({
            title: "Error",
            text: "Ocurrió un problema al procesar la solicitud.",
            icon: "error",
            confirmButtonText: "Entendido",
          });
          console.error("Error:", error);
          submitButton.disabled = false;
          spinner.classList.add("d-none");
        });
    });

  // no pasarse
});
