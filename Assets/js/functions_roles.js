let tableRoles;
document.addEventListener("DOMContentLoaded", function () {
  tableRoles = $("#tableRoles").DataTable({
    ajax: {
      url: base_url + "/Configuracion/getRoles",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { data: "role_name" },
      { data: "estado" },
      {
        data: null,
        render: function (data, type, row) {
          return `
            <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${row.id}">
                <i class="fas fa-archive"></i>
            </button>`;
        },
      },
    ],
  });

  document
    .querySelector("#formRoles")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Configuracion/insertRol";
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
              title: response.message,
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                location.reload();
              }
            });
          } else {
            Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
          }
        }
      };
    });

  $(document).on("click", ".edit-btn", function () {
    const id = $(this).data("id");

    $.ajax({
      url: `${base_url}/Configuracion/getRolbyID/${id}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (!response.status) {
          alert(response.msg || "No se pudo obtener la información del rol.");
          return;
        }

        const data = response.data;

        // Asignar valores básicos
        $("#edit_id").val(data[0].id);
        $("#edit_role_name").val(data[0].role_name);

        // Limpiar tabla antes de llenar
        $("#editForm").empty();

        // Generar filas dinámicamente
        data.forEach((item) => {
          const row = `
            <tr>
              <td>${item.nombre_modulo}</td>
              <td><input type="checkbox" ${
                item.acceder == 1 ? "checked" : ""
              }></td>
              <td><input type="checkbox" ${item.crear == 1 ? "checked" : ""} ${
            item.disabled_crear ? "disabled" : ""
          }></td>
              <td><input type="checkbox" ${item.editar == 1 ? "checked" : ""} ${
            item.disabled_editar ? "disabled" : ""
          }></td>
              <td><input type="checkbox" ${
                item.eliminar == 1 ? "checked" : ""
              } ${item.disabled_eliminar ? "disabled" : ""}></td>
              <td><input type="checkbox" ${
                item.correo == 1 ? "checked" : ""
              }></td>
            </tr>
          `;
          $("#editForm").append(row);
        });
      },
      error: function (xhr, status, error) {
        console.error("Error en la petición:", error);
        alert("Ocurrió un error al obtener la información del rol.");
      },
    });
  });

  // no pasarse
});
