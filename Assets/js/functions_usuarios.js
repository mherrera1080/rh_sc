let tableUsuarios;

document.addEventListener("DOMContentLoaded", function () {
  let permisosMod = permisos[5] || {
    acceder: 0,
    crear: 0,
    editar: 0,
    eliminar: 0,
  };

  // Tabla principal con datos AJAX
  tableUsuarios = $("#tableUsuarios").DataTable({
    ajax: {
      url: base_url + "/Usuarios/selectUsuarios",
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
      { data: "identificacion" },
      { data: "no_empleado" },
      { data: "nombre_completo" },
      { data: "correo" },
      {
        data: null,
        render: function (data, type, row) {
          let botones = "";

          // Botón Editar
          if (permisosMod.editar == 1) {
            botones += `
          <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#updateUser" data-id="${row.id_usuario}">
            <i class="fas fa-archive"></i>
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

  if (document.querySelector("#edit_area")) {
    let ajaxUrl = base_url + "/Configuracion/getSelectArea"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#edit_area").innerHTML = request.responseText;
        $("#edit_area");
      }
    };
  }

  if (document.querySelector("#edit_rol")) {
    let ajaxUrl = base_url + "/Configuracion/getSelectRol"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#edit_rol").innerHTML = request.responseText;
        $("#edit_rol");
      }
    };
  }

  if (document.querySelector("#set_area")) {
    let ajaxUrl = base_url + "/Configuracion/getSelectArea"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#set_area").innerHTML = request.responseText;
        $("#set_area");
      }
    };
  }

  if (document.querySelector("#set_rol")) {
    let ajaxUrl = base_url + "/Configuracion/getSelectRol"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#set_rol").innerHTML = request.responseText;
        $("#set_rol");
      }
    };
  }

  document
    .querySelector("#setUsuarios")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      const pass1 = document.getElementById("set_password").value.trim();
      const pass2 = document
        .getElementById("set_confirm_password")
        .value.trim();

      if (pass1 !== pass2) {
        Swal.fire({
          title: "Error",
          text: "Las contraseñas no coinciden.",
          icon: "error",
          confirmButtonText: "Aceptar",
        });
        return;
      }

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Usuarios/setUsuario";
      let request = new XMLHttpRequest();

      request.open("POST", ajaxUrl, true);
      request.send(formData);

      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
          let response = JSON.parse(request.responseText);

          if (response.status) {
            Swal.fire({
              title: "Éxito",
              text: response.msg,
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then(() => {
              document.querySelector("#setUsuarios").reset();
              $("#setUserModal").modal("hide");
              tableUsuarios.ajax.reload();
            });
          } else {
            Swal.fire({
              title: "Error",
              text: response.msg,
              icon: "error",
              confirmButtonText: "Aceptar",
            });
          }
        }
      };
    });

  $("#setUserModal").on("show.bs.modal", function () {
    $("#setUsuarios")[0].reset(); // Reinicia el formulario
  });

  $(document).on("click", ".edit-btn", function () {
    const id_usuario = $(this).data("id");

    $.ajax({
      url: `${base_url}/Usuarios/selectUserByid/${id_usuario}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#edit_usuario").val(response.data.id_usuario);
          $("#edit_identificacion").val(response.data.identificacion);
          $("#edit_nombres").val(response.data.nombres);
          $("#edit_primer_apellido").val(response.data.primer_apellido);
          $("#edit_segundo_apellido").val(response.data.segundo_apellido);
          $("#edit_codigo").val(response.data.no_empleado);
          $("#edit_correo").val(response.data.correo);
          $("#edit_area").val(response.data.area);
          $("#edit_rol").val(response.data.rol_usuario);
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
    .querySelector("#editUsuarios")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Usuarios/setUsuario";
      let request = new XMLHttpRequest();

      request.open("POST", ajaxUrl, true);
      request.send(formData);

      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
          let response = JSON.parse(request.responseText);

          if (response.status) {
            Swal.fire({
              title: "Éxito",
              text: response.msg,
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then(() => {
              document.querySelector("#setUsuarios").reset();
              $("#updateUser").modal("hide");
              tableUsuarios.ajax.reload();
            });
          } else {
            Swal.fire({
              title: "Error",
              text: response.msg,
              icon: "error",
              confirmButtonText: "Aceptar",
            });
          }
        }
      };
    });
});
