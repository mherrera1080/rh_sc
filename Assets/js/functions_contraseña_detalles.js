let tableFacturas;
let divLoading = document.querySelector("#divLoading");

document.addEventListener("DOMContentLoaded", function () {
  let contraseña = document.querySelector("#contraseña").value;

  tableFacturas = $("#tableFacturas").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    autoWidth: false,
    language: {
      url: base_url + "/Assets/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Contraseñas/getFacturas/" + contraseña,
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
    columnDefs: [
      {
        targets: 0,
        width: "40",
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
          data = data.toLowerCase();
          if (data.includes("pendiente")) {
            html = '<span class="badge badge-warning">PENDIENTE</span>';
          } else if (data.includes("validado")) {
            html = '<span class="badge badge-success">VALIDADO</span>';
          } else if (data.includes("descartar")) {
            html = '<span class="badge badge-danger">DESCARTAR</span>';
          }else if (data.includes("corregir")) {
            html = '<span class="badge badge-danger">CORREGIR</span>';
          } else if (data.includes("descartado")) {
            html = '<span class="badge badge-danger">DESCARTADO</span>';
          }
          return html;
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          if (row.estado_contra === "Pendiente") {
            html = `
            <button type="button" class="btn btn-primary m-0 d-flex justify-content-left d-none btnFacturaEditar"
            data-bs-toggle="modal" data-bs-target="#editarModal" data-id="${row.id_detalle}"> 
            <i class="fas fa-edit"></i>
          </button>
          <button type="button" class="btn btn-primary m-0 d-flex justify-content-left btn-info btnFactura"
            data-bs-toggle="modal" data-bs-target="#infoModal">
            <i class="fas fa-info-circle"></i>
          </button>
          `;
          } else if (row.estado_contra === "Validado Area" && row.estado != "Descartar" ) {
            html = `
          <button type="button" class="btn btn-primary m-0 d-flex justify-content-left btnImpuestosEdit"
            data-bs-toggle="modal" data-bs-target="#impuestoModal" data-id="${row.id_detalle}"> 
            <i class="fas fa-edit"></i>
          </button>`;
          } else {
            html = "";
          }
          return html;
        },
      },
    ],
    
    dom: "lfrtip", // 👈 Esto habilita búsqueda, paginación y selector de registros
    bDestroy: true,
    iDisplayLength: 5, // cantidad de registros por página
    order: [[0, "desc"]],
    language: {
  url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
},

  });

  $(document).on("click", ".btnFacturaEditar", function () {
    const factura = $(this).data("id");

    $.ajax({
      url: `${base_url}/SolicitudFondos/getFacturaId/${factura}`,
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
    .querySelector("#correccionContraseña")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      // Detecta el botón presionado
      let boton = event.submitter;
      let valor = boton.dataset.respuesta;

      let formData = new FormData(this);
      formData.append("respuesta", valor);

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
    .querySelector("#descartarContraseña")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Contraseñas/descartarContraseña";
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

  // document
  //   .querySelector("#regresarContraseña")
  //   .addEventListener("submit", function (event) {
  //     event.preventDefault();
  //     // Detecta el botón presionado
  //     let boton = event.submitter;
  //     let valor = boton.dataset.respuesta;

  //     let formData = new FormData(this);
  //     formData.append("respuesta", valor);

  //     let ajaxUrl = base_url + "/Contraseñas/corregirContraseña";
  //     let request = window.XMLHttpRequest
  //       ? new XMLHttpRequest()
  //       : new ActiveXObject("Microsoft.XMLHTTP");

  //     request.open("POST", ajaxUrl, true);
  //     request.send(formData);

  //     request.onreadystatechange = function () {
  //       if (request.readyState === 4 && request.status === 200) {
  //         let response = JSON.parse(request.responseText);
  //         if (response.status) {
  //           Swal.fire({
  //             title: "Datos guardados correctamente",
  //             icon: "success",
  //             confirmButtonText: "Aceptar",
  //           }).then(() => {
  //             location.reload();
  //           });
  //         } else {
  //           Swal.fire("Atención", response.msg || "Error desconocido", "error");
  //         }
  //       }
  //     };
  //   });


      document
    .querySelector("#regresarContraseña")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Detecta el botón presionado
      let boton = event.submitter;
      let valor = boton.dataset.respuesta;

      let formData = new FormData(this);
      formData.append("respuesta", valor);

      let ajaxUrl = base_url + "/Contraseñas/corregirContraseña";

      // Mostrar loading en el botón
      const originalText = boton.innerHTML;
      boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
      boton.disabled = true;

      let request = new XMLHttpRequest();
      request.open("POST", ajaxUrl, true);

      request.onreadystatechange = function () {
        if (request.readyState === 4) {
          // Restaurar botón siempre
          boton.innerHTML = originalText;
          boton.disabled = false;

          if (request.status === 200) {
            try {
              let data = JSON.parse(request.responseText);
              if (data.status) {
                Swal.fire({
                  title: "Éxito",
                  text: data.message,
                  icon: "success",
                  confirmButtonText: "Aceptar",
                }).then(() => {
                  if (data.reload !== false) {
                    location.reload();
                  }
                });
              } else {
                Swal.fire({
                  title: "Advertencia",
                  text: data.msg || data.message || "Ocurrió un error",
                  icon: "error",
                  confirmButtonText: "Entendido",
                });
              }
            } catch (e) {
              console.error("Error parsing JSON:", e);
              Swal.fire({
                title: "Error",
                text: "Error procesando la respuesta del servidor",
                icon: "error",
                confirmButtonText: "Entendido",
              });
            }
          } else {
            Swal.fire({
              title: "Error",
              text: "Error de conexión: " + request.status,
              icon: "error",
              confirmButtonText: "Entendido",
            });
          }
        }
      };

      request.onerror = function () {
        boton.innerHTML = originalText;
        boton.disabled = false;
        Swal.fire({
          title: "Error",
          text: "Error de conexión con el servidor",
          icon: "error",
          confirmButtonText: "Entendido",
        });
      };

      request.send(formData);
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

      let ajaxUrl = base_url + "/Contraseñas/validacionArea";

      // Mostrar loading en el botón
      const originalText = boton.innerHTML;
      boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
      boton.disabled = true;

      let request = new XMLHttpRequest();
      request.open("POST", ajaxUrl, true);

      request.onreadystatechange = function () {
        if (request.readyState === 4) {
          // Restaurar botón siempre
          boton.innerHTML = originalText;
          boton.disabled = false;

          if (request.status === 200) {
            try {
              let data = JSON.parse(request.responseText);
              if (data.status) {
                Swal.fire({
                  title: "Éxito",
                  text: data.message,
                  icon: "success",
                  confirmButtonText: "Aceptar",
                }).then(() => {
                  if (data.reload !== false) {
                    location.reload();
                  }
                });
              } else {
                Swal.fire({
                  title: "Advertencia",
                  text: data.msg || data.message || "Ocurrió un error",
                  icon: "error",
                  confirmButtonText: "Entendido",
                });
              }
            } catch (e) {
              console.error("Error parsing JSON:", e);
              Swal.fire({
                title: "Error",
                text: "Error procesando la respuesta del servidor",
                icon: "error",
                confirmButtonText: "Entendido",
              });
            }
          } else {
            Swal.fire({
              title: "Error",
              text: "Error de conexión: " + request.status,
              icon: "error",
              confirmButtonText: "Entendido",
            });
          }
        }
      };

      request.onerror = function () {
        boton.innerHTML = originalText;
        boton.disabled = false;
        Swal.fire({
          title: "Error",
          text: "Error de conexión con el servidor",
          icon: "error",
          confirmButtonText: "Entendido",
        });
      };

      request.send(formData);
    });

  document
    .querySelector("#validarContaForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Detecta el botón presionado
      let boton = event.submitter;
      let valor = boton.dataset.respuesta;

      let formData = new FormData(this);
      formData.append("respuesta", valor);

      let ajaxUrl = base_url + "/Contraseñas/validacionConta";
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

  document
    .querySelector("#solicitarFondosForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Contraseñas/solicitudFondos";
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
            $("#solicitarFondos").modal("hide");
          } else {
            Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
          }
        }
      };
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

  let id_proveedor = document.querySelector("#id_proveedor").value;
  let id_area = document.querySelector("#id_area").value;

  if (document.querySelector("#anticipo")) {
    // Concatenamos con una coma entre ambos parámetros
    let ajaxUrl =
      base_url +
      "/SolicitudFondos/getAnticipos/" +
      id_proveedor +
      "," +
      id_area;

    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");

    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#anticipo").innerHTML = request.responseText;
      }
    };
  }

  $(document).on("click", ".btnImpuestosEdit", function () {
    const factura = $(this).data("id");

    $.ajax({
      url: `${base_url}/SolicitudFondos/getFacturaId/${factura}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          // Rellenar campos
          $("#impuesto_id").val(response.data.id_detalle);
          $("#impuesto_id_regimen").val(response.data.id_regimen);
          $("#impuesto_regimen").val(response.data.nombre_regimen);

          $("#impuesto_factura").val(response.data.no_factura);
          $("#impuesto_codax").val(response.data.registro_ax);
          $("#impuesto_servicio").val(response.data.bien_servicio);
          $("#impuesto_documento").val(response.data.valor_documento);

          // IVA
          $("#input_iva").val(response.data.iva_valor);
          if (response.data.iva_valor && response.data.iva_valor > 0) {
            $("#check_iva").prop("checked", true);
            $("#input_iva").prop("disabled", false);
          } else {
            $("#check_iva").prop("checked", false);
            $("#input_iva").prop("disabled", true).val("");
          }

          // ISR
          $("#input_isr").val(response.data.isr_valor);
          if (response.data.isr_valor && response.data.isr_valor > 0) {
            $("#check_isr").prop("checked", true);
            $("#input_isr").prop("disabled", false);
          } else {
            $("#check_isr").prop("checked", false);
            $("#input_isr").prop("disabled", true).val("");
          }

          $("#impuesto_reten_iva").val(response.data.reten_iva);
          $("#impuesto_base").val(response.data.base);
          $("#impuesto_base_iva").val(response.data.iva);
          $("#impuesto_observacion").val(response.data.observacion);
          $("#impuesto_fecha_registro").val(response.data.fecha_registro);

          // ⚡ recalcular después de cargar la data
          calcular();
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  document.addEventListener("submit", function (event) {
    if (event.target && event.target.id === "DetalleImpuesto") {
      event.preventDefault();
      let formData = new FormData(event.target);
      let ajaxUrl = base_url + "/SolicitudFondos/updateDetalle";

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
    }
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
