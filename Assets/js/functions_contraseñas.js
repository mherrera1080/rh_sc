let tableContraseña;

document.addEventListener("DOMContentLoaded", function () {
  // Tabla principal con datos AJAX
  tableContraseña = $("#tableContraseña").DataTable({
    ajax: {
      url: base_url + "/Contraseñas/registroContra",
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
    autoWidth: false,
    colReorder: true,
    columns: [
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
        data: "estado",
        render: function (data, type, row, meta) {
          let html = "";
          data = data.toLowerCase();
          if (data.includes("pendiente")) {
            html = '<span class="badge badge-warning">PENDIENTE</span>';
          } else if (data.includes("validado")) {
            html = '<span class="badge badge-success">VALIDADO</span>';
          } else if (data.includes("corregir")) {
            html = '<span class="badge badge-danger">CORREGIR</span>';
          } else if (data.includes("corregido")) {
            html = '<span class="badge badge-info">CORREGIDO</span>';
          }else if (data.includes("descartado")) {
            html = '<span class="badge badge-danger">DESCARTADO</span>';
          }
          return html;
        },
      },
      {
        title: "Acciones",
        data: "contraseña",
        render: function (data) {
          return `<button class="btn btn-info btn-sm" onclick="window.location.href='${base_url}/Contraseñas/Detalles/${data}'">
                      <i class="fas fa-archive"></i>
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
    language: {
  url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
},

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

  if (document.querySelector("#edit_id_proveedor")) {
    let ajaxUrl = base_url + "/Contraseñas/getSelectProveedor"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#edit_id_proveedor").innerHTML =
          request.responseText;
        $("#edit_id_proveedor");
      }
    };
  }

  if (document.querySelector("#c_id_proveedor")) {
    let ajaxUrl = base_url + "/Contraseñas/getSelectProveedor"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#c_id_proveedor").innerHTML =
          request.responseText;
        $("#c_id_proveedor");
      }
    };
  }

  if (document.querySelector("#edit_area")) {
    let ajaxUrl = base_url + "/Contraseñas/getSelectAreas"; // Ajusta la URL según tu ruta
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

  if (document.querySelector("#c_area")) {
    let ajaxUrl = base_url + "/Contraseñas/getSelectAreas"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#c_area").innerHTML = request.responseText;
        $("#c_area");
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

  //ACTUALIZAR CONTRASEÑA

  $(document).on("click", ".update-btn", function () {
    const contraseña = $(this).data("id");
    $.ajax({
      url: `${base_url}/Contraseñas/getAllContraseña/${contraseña}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          cargarDatosEdicion(response.data);
          $("#setContraseñaEditModal").modal("show");
        } else {
          alert("No se encontraron datos.");
        }
      },
      error: function (error) {
        console.error("Error en AJAX:", error);
      },
    });
  });

  $(document).on("click", ".correccion-btn", function () {
    const contraseña = $(this).data("id");
    $.ajax({
      url: `${base_url}/Contraseñas/getAllContraseña/${contraseña}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          cargarDatosCorreccion(response.data);
          $("#correccionContraseñaModal").modal("show");
        } else {
          alert("No se encontraron datos.");
        }
      },
      error: function (error) {
        console.error("Error en AJAX:", error);
      },
    });
  });

  function cargarDatosEdicion(data) {
    // Llenar los campos de la solicitud principal
    $("#edit_id_contraseña").val(data.id_contraseña);
    $("#edit_contraseña").val(data.contraseña);
    $("#edit_fecha_registro").val(data.fecha_registro);
    $("#edit_id_proveedor").val(data.id_proveedor);
    $("#edit_area").val(data.area);
    $("#edit_fecha_pago").val(data.fecha_pago);

    // Limpiar la tabla de fechas
    const tablaFacturasEdit = $("#tablaFacturasEdit tbody");
    tablaFacturasEdit.empty();

    // Cargar las fechas, valores y días en la tabla
    data.no_factura.forEach((factura, index) => {
      const bien = data.bien_servicio[index];
      const valor = data.valor_documento[index];

      // Añadir fila
      tablaFacturasEdit.append(`
            <tr>
                <td>
                    <input type="text" class="form-control" name="no_factura[]" value="${factura}" required>
                </td>
                <td>
                    <input type="text" class="form-control" name="bien[]" value="${bien}" required>
                </td>
                <td>
                    <input type="text" class="form-control" name="valor[]" value="${valor}" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger eliminarFactura">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `);
    });
  }

  function cargarDatosCorreccion(data) {
    // Llenar los campos de la solicitud principal
    $("#c_id_contraseña").val(data.id_contraseña);
    $("#c_contraseña").val(data.contraseña);
    $("#c_contraseña_hidden").val(data.contraseña);
    $("#c_fecha_registro").val(data.fecha_registro);
    $("#c_id_proveedor").val(data.id_proveedor);
    $("#c_area").val(data.area);
    $("#c_fecha_pago").val(data.fecha_pago);
    $("#c_correcciones").val(data.correcciones);

    // Limpiar la tabla de fechas
    const tablaFacturasCorreccion = $("#tablaFacturasCorreccion tbody");
    tablaFacturasCorreccion.empty();

    // Cargar las fechas, valores y días en la tabla
    data.no_factura.forEach((factura, index) => {
      const bien = data.bien_servicio[index];
      const valor = data.valor_documento[index];
      const observacion = data.observacion[index];
      const estado = data.estado[index];

      // IF según el estado
      if (estado === "Corregir") {
        // <-- Aquí defines el filtro
        tablaFacturasCorreccion.append(`
      <tr>
          <td>
              <input type="text" class="form-control" name="no_factura[]" value="${factura}" required>
          </td>
          <td>
              <input type="text" class="form-control" name="bien[]" value="${bien}" required>
          </td>
          <td>
              <input type="text" class="form-control" name="valor[]" value="${valor}" required>
          </td>
          <td>
              <input type="text" class="form-control" name="observacion[]" value="${observacion}" disabled>
          </td>
          <td>
              <input type="text" class="form-control" name="estado[]" value="${estado}" disabled>
          </td>
          <td>
              <button type="button" class="btn btn-danger eliminarFacturaCorreccion">
                  <i class="fas fa-trash-alt"></i>
              </button>
          </td>
      </tr>
    `);
      }
    });
  }

  $("#agregarFacturaEdit").on("click", function () {
    $("#tablaFacturasEdit tbody").append(`
          <tr>
            <td><input type="text" class="form-control factura" name="no_factura[]" placeholder="123456789" required></td>
            <td><input type="text" class="form-control bien" name="bien[]" required></td>
            <td><input type="text" class="form-control valor" name="valor[]" placeholder="1000.00" required></td>
            <td>
                <button type="button" class="btn btn-danger eliminarFila">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
          </tr>
      `);
  });

  $("#tablaFacturasEdit").on("click", ".eliminarFila", function () {
    $(this).closest("tr").remove();
  });

  $("#agregarFacturaCorreccion").on("click", function () {
    $("#tablaFacturasCorreccion tbody").append(`
          <tr>
            <td><input type="text" class="form-control factura" name="no_factura[]" placeholder="123456789" required></td>
            <td><input type="text" class="form-control bien" name="bien[]" required></td>
            <td><input type="text" class="form-control valor" name="valor[]" placeholder="1000.00" required></td>
            <td><input type="text" class="form-control observacion" name="observacion[]" placeholder="N/A" disabled></td>
            <td><input type="text" class="form-control estado" name="estado[]" placeholder="Nuevo" disabled></td>
            <td>
                <button type="button" class="btn btn-danger eliminarFila">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
          </tr>
      `);
  });

  $("#tablaFacturasCorreccion").on("click", ".eliminarFila", function () {
    $(this).closest("tr").remove();
  });

  $(document).on("click", ".eliminarFactura", function () {
    const row = $(this).closest("tr"); // Fila actual
    const contraseña = $("#edit_contraseña").val(); // ID de la solicitud
    const factura = row.find("input[name='no_factura[]']").val(); // Fecha seleccionada

    if (confirm(`¿Estás seguro de eliminar la factura ${factura}?`)) {
      $.ajax({
        url: `${base_url}/Contraseñas/eliminarFactura`,
        method: "POST",
        data: {
          contraseña: contraseña,
          factura: factura,
        },
        dataType: "json",
        success: function (response) {
          if (response.status) {
            alert(response.message); // Mensaje de éxito
            row.remove(); // Eliminar la fila de la tabla
          } else {
            alert(response.message); // Mensaje de error
          }
        },
        error: function (error) {
          console.error("Error al eliminar la factura:", error);
          alert("Ocurrió un error al intentar eliminar la factura.");
        },
      });
    }
  });

  $(document).on("click", ".eliminarFacturaCorreccion", function () {
    const row = $(this).closest("tr"); // Fila actual
    const contraseña = $("#c_contraseña").val(); // ID de la solicitud
    const factura = row.find("input[name='no_factura[]']").val(); // Fecha seleccionada

    if (confirm(`¿Estás seguro de eliminar la factura ${factura}?`)) {
      $.ajax({
        url: `${base_url}/Contraseñas/eliminarFactura`,
        method: "POST",
        data: {
          contraseña: contraseña,
          factura: factura,
        },
        dataType: "json",
        success: function (response) {
          if (response.status) {
            alert(response.message); // Mensaje de éxito
            row.remove(); // Eliminar la fila de la tabla
          } else {
            alert(response.message); // Mensaje de error
          }
        },
        error: function (error) {
          console.error("Error al eliminar la Factura:", error);
          alert("Ocurrió un error al intentar eliminar la factura.");
        },
      });
    }
  });

  document
    .getElementById("updateContraseña")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      // Recoge los valores de las fechas, valores y días
      const factura = Array.from(
        document.querySelectorAll(
          "#tablaFacturasEdit input[name='no_factura[]']"
        )
      ).map((input) => input.value);

      const bien = Array.from(
        document.querySelectorAll("#tablaFacturasEdit input[name='bien[]']")
      ).map((select) => select.value);

      const valor = Array.from(
        document.querySelectorAll("#tablaFacturasEdit input[name='valor[]']")
      ).map((select) => select.value);

      // Envía la solicitud vía AJAX
      const formData = new FormData(this);

      factura.forEach((factura, index) => {
        formData.append(`factura[${index}]`, factura);
        formData.append(`bien[${index}]`, bien[index]);
        formData.append(`valor[${index}]`, valor[index]);
      });

      fetch(base_url + "/Contraseñas/actualizarContraseña", {
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
          } else {
            Swal.fire({
              title: "Advertencia",
              text: data.message,
              icon: data.type === "warning" ? "warning" : "error", // Dependiendo del tipo
              confirmButtonText: "Entendido",
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload();
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

  document
    .getElementById("correccionContraseña")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      // Recoge los valores de las fechas, valores y días
      const factura = Array.from(
        document.querySelectorAll(
          "#tablaFacturasCorreccion input[name='no_factura[]']"
        )
      ).map((input) => input.value);

      const bien = Array.from(
        document.querySelectorAll(
          "#tablaFacturasCorreccion input[name='bien[]']"
        )
      ).map((select) => select.value);

      const valor = Array.from(
        document.querySelectorAll(
          "#tablaFacturasCorreccion input[name='valor[]']"
        )
      ).map((select) => select.value);

      // Envía la solicitud vía AJAX
      const formData = new FormData(this);

      factura.forEach((factura, index) => {
        formData.append(`factura[${index}]`, factura);
        formData.append(`bien[${index}]`, bien[index]);
        formData.append(`valor[${index}]`, valor[index]);
      });

      fetch(base_url + "/Contraseñas/correccionContraseña", {
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
          } else {
            Swal.fire({
              title: "Advertencia",
              text: data.message,
              icon: data.type === "warning" ? "warning" : "error", // Dependiendo del tipo
              confirmButtonText: "Entendido",
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload();
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

  // no pasarse
});
