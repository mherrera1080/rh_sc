let tableContraseña;

document.addEventListener("DOMContentLoaded", function () {
  // Tabla principal con datos AJAX
  tableContraseña = $("#tableContraseña").DataTable({
    ajax: {
      url: base_url + "/Contraseñas/contraseñasAreas",
      type: "POST",
      data: function (d) {
        d.id_area = 3; // 🔥 aquí quemas el área que quieras
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
      { data: "contraseña" },
      { data: "area" },
      { data: "fecha_registro" },
      { data: "proveedor" },
      { data: "monto_total" },
      { data: "fecha_pago" },
      { data: "estado" },
      {
        data: null,
        render: function (data, type, row) {
          return `<button class="btn btn-info btn-sm" onclick="window.location.href='${base_url}/Contraseñas/Facturas/${row.contraseña}'">
                    <i class="fas fa-archive"></i>
                  </button>`;
        },
      },
    ],
    dom: "Bfrtip",
  });

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

  if (document.querySelector("#area")) {
    let ajaxUrl = base_url + "/Contraseñas/getSelectAreas"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#area").innerHTML = request.responseText;
        $("#area");
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

      fetch(base_url + "/Contraseñas/guardarContraseña", {
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
            text: "Ocurrió un problema al procesar la solicitud.",
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

  // no pasarse
});
