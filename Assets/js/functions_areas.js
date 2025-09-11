let tableAreas;
document.addEventListener("DOMContentLoaded", function () {
  tableAreas = $("#tableAreas").DataTable({
    ajax: {
      url: base_url + "/Configuracion/getAreas",
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
        render: function (data, type, row) {
          return `
          <button type="button" class="btn btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#modalEditarArea" data-id="${row.id_area}">
            <i class="fas fa-edit"></i>
          </button>`;
        },
      },
    ],
    dom: "Bfrtip",
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
