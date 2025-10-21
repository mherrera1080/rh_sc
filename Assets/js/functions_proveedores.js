let tableProveedores;
document.addEventListener("DOMContentLoaded", function () {
  tableProveedores = $("#tableProveedores").DataTable({
    ajax: {
      url: base_url + "/Configuracion/getProveedores",
    },
    autoWidth: false,
    colReorder: true,
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { data: "nombre_proveedor" },
      { data: "nombre_social" },
      { data: "nombre_regimen" },
      { data: "nit_proveedor" },
      { data: "fecha_creacion" },
      { data: "dias_credito" },
      { data: "estado" },
      {
        data: null,
        render: function (data, type, row) {
          return `
          <button type="button" class="btn btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#modalEditarProveedor" data-id="${row.id_proveedor}">
            <i class="fas fa-edit"></i>
          </button>`;
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
  });

  document
    .querySelector("#formNuevoProveedor")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Configuracion/setProveedor";
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
                tableProveedores.ajax.reload();
              }
            });
            $("#modalNuevoProveedor").modal("hide");
          } else {
            Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
          }
        }
      };
    });

  $(document).on("click", ".edit-btn", function () {
    const id_proveedor = $(this).data("id");

    $.ajax({
      url: `${base_url}/Configuracion/getProveedorID/${id_proveedor}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#edit_id_proveedor").val(response.data.id_proveedor);
          $("#edit_nombre_proveedor").val(response.data.nombre_proveedor);
          $("#edit_nombre_social").val(response.data.nombre_social);
          $("#edit_nit_proveedor").val(response.data.nit_proveedor);
          $("#edit_regimen").val(response.data.regimen);
          $("#edit_estado").val(response.data.estado);
          $("#edit_dias_credito").val(response.data.dias_credito);
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
    .querySelector("#formEditarProveedor")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Configuracion/setProveedor";
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
                tableProveedores.ajax.reload();
              }
            });
            $("#modalEditarProveedor").modal("hide");
          } else {
            Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
          }
        }
      };
    });

  // no pasarse
});
