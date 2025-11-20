let tableRoles;
document.addEventListener("DOMContentLoaded", function () {
  let permisosMod = permisos[9] || {
    acceder: 0,
    crear: 0,
    editar: 0,
    eliminar: 0,
  };
  tableRoles = $("#tableRoles").DataTable({
    ajax: {
      url: base_url + "/Configuracion/getRoles",
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
        className: "text-center",
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },
      { data: "role_name" },
      {
        data: "estado",
        className: "text-center",
        render: function (data) {
          // Mostrar badge visual según estado
          return data === "Activo"
            ? '<span class="badge bg-success">Activo</span>'
            : '<span class="badge bg-danger">Inactivo</span>';
        },
      },
      {
        data: null,
        className: "text-center",
        orderable: false,
        render: function (data, type, row) {
          let botones = "";

          // Botón Editar
          if (permisosMod.editar == 1) {
            botones += `
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-primary edit-btn me-1" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editModal" 
                    data-id="${row.id}" 
                    title="Editar rol">
              <i class="fas fa-edit"></i>
            </button>
          </div>`;
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
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },
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

  $(document).on("click", "#submit", function () {
    const idRol = $("#edit_id").val(); // Obtener el ID del rol
    const permisos = []; // Crear un array para almacenar los permisos

    // Iterar sobre cada fila de permisos
    $("#editForm tr").each(function () {
      const moduloNombre = $(this).find("td").first().text(); // Obtener el nombre del módulo
      const acceder = $(this)
        .find("input[type='checkbox']")
        .eq(0)
        .is(":checked")
        ? 1
        : 0;
      const crear = $(this).find("input[type='checkbox']").eq(1).is(":checked")
        ? 1
        : 0;
      const editar = $(this).find("input[type='checkbox']").eq(2).is(":checked")
        ? 1
        : 0;
      const eliminar = $(this)
        .find("input[type='checkbox']")
        .eq(3)
        .is(":checked")
        ? 1
        : 0;

      permisos.push({ moduloNombre, crear, acceder, editar, eliminar });
    });

    // Enviar los datos al servidor
    fetch(`${base_url}/Configuracion/updatePermissions`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ idRol, permisos }), // Enviar los datos como JSON
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status) {
          Swal.fire({
            title: "Operación Exitosa!",
            text: data.msg,
            icon: "success",
            confirmButtonText: "Aceptar",
          });
          // .then((result) => {
          //   if (result.isConfirmed) {
          //     // Recargar la página al presionar "Aceptar"
          //     location.reload();
          //   }
          // });
        } else {
          alert(data.msg);
        }
      })
      .then((response) => response.json())
      .then((data) => {
        if (data.status) {
          // Limpiar la tabla antes de llenarla
          $("#editForm").empty();

          // Iterar sobre los módulos y permisos para llenar la tabla
          data.data.forEach(function (item) {
            const row = `
            <tr>
                <td>${item.nombre}</td>
                <td><input type="checkbox" 
                ${item.crear ? "checked" : ""} /></td>
                <td><input type="checkbox" 
                ${item.acceder ? "checked" : ""} /></td>
                <td><input type="checkbox" 
                ${item.editar ? "checked" : ""} /></td>
                <td><input type="checkbox" ${
                  item.eliminar ? "checked" : ""
                } /></td>
            </tr>`;
            $("#editForm").append(row);
          });
        }
      })
      .catch((error) => console.log("Error:", error));
  });

  // no pasarse
});
