let tableAreas;
document.addEventListener("DOMContentLoaded", function () {
  let permisosMod = permisos[6] || {
    acceder: 0,
    crear: 0,
    editar: 0,
    eliminar: 0,
  };
  tableAreas = $("#tableAreas").DataTable({
    ajax: {
      url: base_url + "/Configuracion/getAreas",
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
      { data: "nombre_area" },
      { data: "estado" },
      {
        data: null,
        title: "Acciones",
        render: function (data, type, row) {
          let botones = "";

          // Botón Editar
          if (permisosMod.editar == 1) {
            botones += `
          <button type="button" class="btn btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#modalEditarArea" data-id="${row.id_area}">
            <i class="fas fa-edit"></i>
          </button>`;
          } else {
            botones += `
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
              <i class="fas fa-pencil-square"></i>
            </button>`;
          }

          return botones;
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

  document
    .querySelector("#formNuevaArea")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Configuracion/setArea";
      let request = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");

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
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                location.reload();
              }
            });
            $("#createModal").modal("hide");
          } else {
            Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
          }
        }
      };
    });

  $(document).on("click", ".edit-btn", function () {
    const idEmpresa = $(this).data("id");

    $.ajax({
      url: `${base_url}/Configuracion/getAreabyID/${idEmpresa}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#edit_id_area").val(response.data.id_area);
          $("#edit_nombre").val(response.data.nombre_area);
          $("#edit_estado").val(response.data.estado);
        } else {
          Swal.fire({
            title: "Error",
            text: "Hubo un problema al procesar la solicitud.",
            icon: "error",
            confirmButtonText: "Aceptar",
          });
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  document
    .querySelector("#formEditarArea")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Configuracion/setArea";
      let request = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");

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
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                tableAreas.ajax.reload();
              }
            });
            $("#editModal").modal("hide");
          } else {
            Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
          }
        }
      };
    });

  // no pasarse
});
