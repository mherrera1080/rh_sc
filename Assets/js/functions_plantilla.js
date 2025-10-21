let tablePlantilla;

function cargarTablaPlantilla(id_area) {
  if (tablePlantilla) {
    tablePlantilla.destroy();
    $("#tablePlantilla").empty(); // Limpia header y body
  }

  fetch(base_url + "/Modulos/contraseñasAreas/" + id_area)
    .then((res) => res.json())
    .then((response) => {
      if (!response.status) {
        Swal.fire({
          title: "Advertencia",
          text: response.msg,
          icon: response.type === "warning" ? "warning" : "error", // Dependiendo del tipo
          confirmButtonText: "Entendido",
        });
      }

      const datos = response.data;

      let columns = [
        {
          data: null,
          title: "#",
          render: function (data, type, row, meta) {
            return meta.row + 1; // contador
          },
        },
        { data: "contraseña", title: "Contraseña" },
        { data: "area", title: "Área" },
        { data: "fecha_registro", title: "Registro" },
        { data: "proveedor", title: "Proveedor" },
        { data: "monto_total", title: "Total" },
        { data: "fecha_pago", title: "Fecha Pago" },
        {
          data: "estado", title: "Estado",
          render: function (data, type, row, meta) {
            let html = "";
            if (data == "Pendiente") {
              html = '<span class="badge badge-warning">PENDIENTE</span>';
            } else if (data == "Validado") {
              html = '<span class="badge badge-success">VALIDADO</span>';
            } else if (data == "Correccion") {
              html = '<span class="badge badge-danger">CORREGIR</span>';
            } else if (data == "Descartado") {
              html = '<span class="badge badge-danger">DESCARTADO</span>';
            }
            return html;
          },
        },
        {
          title: "Acciones",
          data: "contraseña",
          render: function (data) {
            return `<button class="btn btn-info btn-sm" onclick="window.location.href='${base_url}/Contraseñas/Facturas/${data}'">
                      <i class="fas fa-archive"></i>
                    </button>`;
          },
        },
      ];

      tablePlantilla = $("#tablePlantilla").DataTable({
        data: datos,
        columns: columns,
        dom: "Bfrtip",
      });
    })
    .catch((error) => {
      console.error("Error al cargar datos de contraseñas:", error);
    });
}

document.addEventListener("DOMContentLoaded", function () {
  let id_area = document.querySelector("#area").value;
  cargarTablaPlantilla(id_area);

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

  if (document.querySelector("#proveedor_recibimiento")) {
    let ajaxUrl = base_url + "/Contraseñas/getSelectProveedor"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#proveedor_recibimiento").innerHTML =
          request.responseText;
        $("#proveedor_recibimiento");
      }
    };
  }

  const agregarFacturaBtn = document.getElementById("agregarFactura");
  agregarFacturaBtn.addEventListener("click", () => {
    const nuevaFila = document.createElement("tr");
    nuevaFila.innerHTML = `
    <td><input type="text" class="form-control factura" name="factura[]" placeholder="123456789" required></td>
    <td><input type="text" class="form-control bien" name="bien[]" required></td>
    <td><input type="text" class="form-control valor" name="valor[]" placeholder="1000.00" required></td>
    <td>
        <button type="button" class="btn btn-danger eliminarFila">
            <i class="fas fa-trash-alt"></i>
        </button>
    </td>`;

    tablaFacturas.querySelector("tbody").appendChild(nuevaFila);

    const eliminarBtn = nuevaFila.querySelector(".eliminarFila");
    eliminarBtn.addEventListener("click", () => {
      nuevaFila.remove();
    });
  });

  document
    .getElementById("setContraseña")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      // Recoge los valores de las fechas y los días
      const factura = Array.from(
        document.querySelectorAll("input[name='factura[]']")
      ).map((input) => input.value);

      const bien = Array.from(
        document.querySelectorAll("input[name='bien[]']")
      ).map((input) => input.value);

      const valor = Array.from(
        document.querySelectorAll("input[name='valor[]']")
      ).map((input) => input.value);

      // Envía la solicitud vía AJAX
      const formData = new FormData(this);
      const spinner = document.querySelector("#spinerSubmit ");
      const submitButton = document.querySelector("#btnSubmit");

      submitButton.disabled = true;
      spinner.classList.remove("d-none");

      factura.forEach((factura, index) => {
        formData.append(`factura[${index}]`, factura);
        formData.append(`bien[${index}]`, bien[index]);
        formData.append(`valor[${index}]`, valor[index]);
      });

      fetch(base_url + "/Modulos/guardarContraseña", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status) {
            Swal.fire({
              title: "Éxito",
              text: data.message,
              icon: "success", // Icono de éxito
              confirmButtonText: "OK",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                location.reload();
              }
            });
            $("#setContraseñaModal").modal("hide");
            tableContraseña.ajax.reload();
          } else {
            Swal.fire({
              title: "Advertencia",
              text: data.message,
              icon: data.type === "warning" ? "warning" : "error", // Dependiendo del tipo
              confirmButtonText: "Entendido",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                submitButton.disabled = false;
                spinner.classList.add("d-none");
              }
            });
          }
        })
        .catch((error) => {
          Swal.fire({
            title: "Error",
            text: data.message,
            icon: "error",
            confirmButtonText: "Entendido",
          });
          console.error("Error:", error);
        });
    });

  $("#setContraseñaModal").on("hidden.bs.modal", function () {
    // Limpiar campos del formulario
    $("#setContraseña")[0].reset(); // Reinicia el formulario
    $("#tablaFacturas tbody td").remove();
  });

  $(document).on("input", ".factura", function () {
    this.value = this.value.replace(/\D/g, ""); // Solo dígitos
  });

  // Validar números decimales (acepta punto o coma)
  $(document).on("input", ".valor", function () {
    this.value = this.value
      .replace(/[^0-9.,]/g, "") // Quitar caracteres no válidos
      .replace(/(,|\.){2,}/g, "$1") // Evita múltiples puntos/comas seguidos
      .replace(/^(\.|,)/g, ""); // No permitir punto/coma al principio
  });

  //No pirir
});
