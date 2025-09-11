let tableUsuarios;

document.addEventListener("DOMContentLoaded", function () {
  // Tabla principal con datos AJAX
  tableUsuarios = $("#tableUsuarios").DataTable({
    ajax: {
      url: base_url + "/Usuarios/selectUsuarios",
    },
    columns: [
      { data: "id_usuario" },
      { data: "identificacion" },
      { data: "no_empleado" },
      { data: "nombre_completo" },
      { data: "correo" },
      { data: null },
    ],
    dom: "Bfrtip",
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
  });

  document
    .querySelector("#setUsuarios")
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
});
