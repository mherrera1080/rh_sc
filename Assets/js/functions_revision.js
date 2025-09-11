let tableFacturas;
let divLoading = document.querySelector("#divLoading");

document.addEventListener("DOMContentLoaded", function () {
  let contraseña = document.querySelector("#contraseña").value;
  // Inicializar DataTable
  tableFacturas = $("#tableFacturas").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    autoWidth: false,
    language: {
      url: base_url + "/Assets/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Contabilidad/getFacturas/" + contraseña,
    },
    columnDefs: [
      {
        targets: 0, // índice de la columna del checkbox
        width: "40", // o "30px" según necesites
        className: "text-center",
      },
    ],
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },
      { data: "no_factura" },
      { data: "bien_servicio" },
      { data: "valor_documento" },
      {
        data: "estado",
        render: function (data, type, row, meta) {
          let html = "";
          if (data == "Pendiente") {
            html = '<span class="badge badge-warning">PENDIENTE</span>';
          } else if (data == "Validado") {
            html = '<span class="badge badge-success">VALIDADO</span>';
          } else if (data == "Corregir") {
            html = '<span class="badge badge-danger">CORREGIR</span>';
          }
          return html;
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          return `
          <button type="button" class="btn btn-primary m-0 d-flex justify-content-left d-none btnFacturaEditar"
            data-bs-toggle="modal" data-bs-target="#editarModal" data-id="${row.id_detalle}"> 
            <i class="fas fa-edit"></i>
          </button>
          <button type="button" class="btn btn-primary m-0 d-flex justify-content-left btn-info btnFactura"
            data-bs-toggle="modal" data-bs-target="#infoModal">
            <i class="fas fa-info-circle"></i>
          </button>
          `;
        },
      },
    ],
    dom: "",
    bDestroy: true,
    iDisplayLength: 5,
    order: [[0, "desc"]],
  });

  $(document).on("click", ".btnFacturaEditar", function () {
    const factura = $(this).data("id");

    $.ajax({
      url: `${base_url}/Contraseñas/getFacturaId/${factura}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#edit_id").val(response.data.id_detalle);
          $("#edit_factura").val(response.data.no_factura);
          $("#edit_servicio").val(response.data.bien_servicio);
          $("#edit_documento").val(response.data.valor_documento);
          $("#edit_fecha_registro").val(response.data.fecha_registro);
          $("#edit_estado").val(response.data.estado);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  document
    .querySelector("#FacturaEdit")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Contraseñas/updateFactura";
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
                tableFacturas.ajax.reload();
                $("#editarModal").modal("hide");
              }
            });
          } else {
            Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
          }
        }
      };
    });

  document
    .querySelector("#correccionContraseña")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Contraseñas/corregirContraseña";
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
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire("Atención", response.msg || "Error desconocido", "error");
          }
        }
      };
    });

  document
    .querySelector("#validarForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Detecta el botón presionado
      let boton = event.submitter;
      let valor = boton.dataset.respuesta;

      let formData = new FormData(this);
      formData.append("respuesta", valor);

      let ajaxUrl = base_url + "/Contraseñas/validarContraseña";
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
    });

  //bla bla bla
});

function toggleInputs() {
  // Mostrar botones de acción
  document.getElementById("btnCancelar").style.display = "inline-block";

  // Ocultar botones de edición
  document.getElementById("btnEditar").style.display = "none";
  document.getElementById("btnValidar").style.display = "none";
  document.getElementById("btnCorregir").style.display = "none";

  const btnsEditar = document.getElementsByClassName("btnFacturaEditar");
  for (let btn of btnsEditar) {
    btn.classList.remove("d-none");
  }

  // Ocultar botones de info de factura
  const btnsInfo = document.getElementsByClassName("btnFactura");
  for (let btn of btnsInfo) {
    btn.classList.add("d-none");
  }
}

function CancelEdit() {
  // Ocultar botón Cancelar y Actualizar
  document.getElementById("btnCancelar").style.display = "none";
  // Mostrar botón Editar y Devolución
  document.getElementById("btnEditar").style.display = "inline-block";
  document.getElementById("btnValidar").style.display = "inline-block";
  document.getElementById("btnCorregir").style.display = "inline-block";

  const btnsEditar = document.getElementsByClassName("btnFacturaEditar");
  for (let btn of btnsEditar) {
    btn.classList.add("d-none");
  }

  // Ocultar botones de info de factura
  const btnsInfo = document.getElementsByClassName("btnFactura");
  for (let btn of btnsInfo) {
    btn.classList.remove("d-none");
  }
}
