let tableFacturas;
let divLoading = document.querySelector("#divLoading");

document.addEventListener("DOMContentLoaded", function () {
  let id_solicitud = document.querySelector("#id_solicitud").value;
  let solicitud_estado = document.querySelector("#solicitud_estado").value;
  let usuario = document.querySelector("#area_usuario").value;

  tableFacturas = $("#tableFacturas").DataTable({
    ajax: {
      url: base_url + "/SolicitudFondos/getFacturasSolicitud/" + id_solicitud,
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
      { data: "estado", title: "#" },
      { data: "no_comparativa", title: "No. Comparativa", visible: false },
      { data: "no_oc", title: "No. O.C.", visible: false },
      { data: "registro_ax", title: "Registro AX", visible: false },
      { data: "bien_servicio", title: "Bien/Servicio" },
      { data: "valor_documento", title: "Total" },
      { data: "fecha_registro", title: "Fecha Registro", visible: false },
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

  // =============================
  // 📌 Guardar cambios en detalle
  // =============================

  document.addEventListener("submit", function (event) {
    if (event.target && event.target.id === "finalizarSolicitud") {
      event.preventDefault();

      // Detecta el botón presionado
      let boton = event.submitter;
      let valor = boton.dataset.respuesta;
      let formData = new FormData(event.target);
      formData.append("respuesta", valor);

      let ajaxUrl = base_url + "/SolicitudFondos/finalizarSolicitudSinContra";
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

  $(document).on("click", ".btnFacturaEditar", function () {
    const solicitud = $(this).data("id");
    $.ajax({
      url: `${base_url}/SolicitudFondos/getSolisinContra/${solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          cargarDatosEdicion(response.data);
          $("#editarSolicitud").modal("show");
        } else {
          alert("No se encontraron datos 505");
        }
      },
      error: function (error) {
        console.error("Error en AJAX:", error);
      },
    });
  });

  function cargarDatosEdicion(data) {
    // Llenar los campos de la solicitud principal
    $("#edit_id_solicitud").val(data.id_solicitud);
    $("#edit_proveedor").val(data.proveedor_id);
    $("#edit_observacion").val(data.observacion);
    $("#edit_fecha_creacion").val(data.fecha_creacion);
    $("#edit_fecha_pago").val(data.fecha_pago);

    // Limpiar la tabla de fechas
    const tablaFacturasEdit = $("#tablaFacturasEdit tbody");
    tablaFacturasEdit.empty();

    // Cargar las fechas, valores y días en la tabla
    data.no_factura.forEach((factura, index) => {
      const tipo = data.tipo[index];
      const bien = data.bien_servicio[index];
      const valor = data.valor_documento[index];

      // Añadir fila
      tablaFacturasEdit.append(`
            <tr>
                <td>
                    <input type="text" class="form-control" name="no_factura[]" value="${factura}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control" name="tipo[]" value="${tipo}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control" name="bien[]" value="${bien}" required>
                </td>
                <td>
                    <input type="text" class="form-control" name="valor[]" value="${valor}" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger eliminarFactura">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `);
    });
  }

  if (document.querySelector("#edit_proveedor")) {
    let ajaxUrl = base_url + "/Contraseñas/getSelectProveedor"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#edit_proveedor").innerHTML =
          request.responseText;
        $("#edit_proveedor");
      }
    };
  }

  let respuestaSeleccionada = null;

  // Detecta qué botón se presionó antes del submit
  document
    .querySelectorAll("#setSolicitud button[type='submit']")
    .forEach((btn) => {
      btn.addEventListener("click", function () {
        respuestaSeleccionada = this.dataset.respuesta;
      });
    });

  document
    .getElementById("setSolicitud")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const factura = Array.from(
        document.querySelectorAll(
          "#tablaFacturasEdit input[name='no_factura[]']"
        )
      ).map((input) => input.value);

      const bien = Array.from(
        document.querySelectorAll("#tablaFacturasEdit input[name='bien[]']")
      ).map((input) => input.value);

      const valor = Array.from(
        document.querySelectorAll("#tablaFacturasEdit input[name='valor[]']")
      ).map((input) => input.value);

      const formData = new FormData(this);

      // ✅ Añadir respuesta detectada
      formData.append("respuesta", respuestaSeleccionada);

      factura.forEach((factura, index) => {
        formData.append(`factura[${index}]`, factura);
        formData.append(`bien[${index}]`, bien[index]);
        formData.append(`valor[${index}]`, valor[index]);
      });

      // 🔍 Comprobación en consola (opcional)
      for (const [key, val] of formData.entries()) {
        console.log(key, "=>", val);
      }

      fetch(base_url + "/SolicitudFondos/actualizarSolicitud", {
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
            }).then((result) => {
              if (result.isConfirmed) location.reload();
            });
          } else {
            Swal.fire({
              title: "Advertencia",
              text: data.message,
              icon: data.type === "warning" ? "warning" : "error",
              confirmButtonText: "Entendido",
            }).then((result) => {
              if (result.isConfirmed) location.reload();
            });
          }
        })
        .catch((error) => {
          Swal.fire({
            title: "Error",
            text: "Ocurrió un problema al procesar la solicitud.",
            icon: "error",
            confirmButtonText: "Entendido",
          });
          console.error("Error:", error);
        });
    });

  // no borrar
});
