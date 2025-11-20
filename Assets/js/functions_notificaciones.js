let tableGrupoCorreos;
document.addEventListener("DOMContentLoaded", function () {
  let permisosMod = permisos[8] || {
    acceder: 0,
    crear: 0,
    editar: 0,
    eliminar: 0,
  };
  tableGrupoCorreos = $("#tableGrupoCorreos").DataTable({
    ajax: {
      url: base_url + "/Configuracion/getGrupoCorreos",
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
      { data: "nombre_grupo" },
      { data: "area" },
      { data: "categoria" },
      { data: "estado" },
      {
        data: null,
        title: "Acciones",
        render: function (data, type, row) {
          let botones = "";

          // Botón Editar
          if (permisosMod.editar == 1) {
            botones += `
          <button type="button" class="btn btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#modalGrupoCorreos" data-id="${row.id_grupo_correo}">
            <i class="fas fa-edit"></i>
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

  if (document.querySelector("#set_area")) {
    let ajaxUrl = base_url + "/Contraseñas/getSelectAreas"; // Ajusta la URL según tu ruta
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

  if (document.querySelector("#set_categoria")) {
    let ajaxUrl = base_url + "/Configuracion/getSelectCategoriaCorreos"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#set_categoria").innerHTML =
          request.responseText;
        $("#set_categoria");
      }
    };
  }

  if (document.querySelector("#edit_areas")) {
    let ajaxUrl = base_url + "/Contraseñas/getSelectAreas"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#edit_areas").innerHTML = request.responseText;
        $("#edit_areas");
      }
    };
  }

  if (document.querySelector("#edit_categoria")) {
    let ajaxUrl = base_url + "/Configuracion/getSelectCategoriaCorreos"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#edit_categoria").innerHTML =
          request.responseText;
        $("#edit_categoria");
      }
    };
  }

  document
    .querySelector("#formGrupoCorreo")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Configuracion/setGrupoCorreo";
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
            $("#createModal").modal("hide");
          } else {
            Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
          }
        }
      };
    });

  $(document).on("click", ".edit-btn", function () {
    const id_grupo = $(this).data("id");
    cargarDatosGrupo(id_grupo);
  });

  function cargarDatosGrupo(id_grupo) {
    $.ajax({
      url: `${base_url}/Configuracion/getGruposCorreoByID/${id_grupo}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status && response.data && response.data.grupo) {
          const grupo = response.data.grupo;

          // Datos básicos
          $("#edit_id_grupo").val(grupo.id_grupo_correo);
          $("#edit_nombre_grupo").val(grupo.nombre_grupo);
          $("#edit_areas").val(grupo.area);
          $("#edit_categoria").val(grupo.categoria);

          // Cargar fases
          const fases = response.data.fases || [];
          cargarFasesGrupo(fases);

          // Abrir modal
          $("#modalGrupoCorreos").modal("show");
        } else {
          console.error("Error en respuesta:", response); // DEBUG
          alert(response.message);
        }
      },
      error: function (err) {
        console.error("Error al cargar grupo:", err);
      },
    });
  }

  function cargarFasesGrupo(fases) {
    const contenedor = $("#contenedorFasesCorreos");
    contenedor.empty();

    if (fases.length === 0) {
      contenedor.html(
        '<div class="alert alert-info">No hay fases configuradas para esta categoría.</div>'
      );
      return;
    }

    fases.forEach((fase, index) => {
      const card = document.createElement("div");
      card.classList.add("card", "mb-3", "shadow-sm");
      card.dataset.idFase = fase.id_fase;

      card.innerHTML = `
        <div class="card-header d-flex justify-content-between align-items-center">
            <span> <strong>Fase: ${fase.nombre_base}</strong> </span>
            <button type="button" class="btn-close btn-close-white btn-delete-fase" title="Eliminar fase"></button>
        </div>
        <div class="card-body">
            <div class="usuarios-fase d-flex flex-wrap gap-2">
                <!-- Usuarios se apilan horizontalmente -->
            </div>
            <button type="button" class="btn btn-sm btn-success mt-2 btn-add-usuario">
                <i class="fas fa-user-plus me-1"></i> Agregar Usuario
            </button>
        </div>
        `;

      contenedor[0].appendChild(card);

      const usuariosContainer = card.querySelector(".usuarios-fase");

      // Función para agregar un select de usuario
      function agregarUsuario(usuarioId = null) {
        const usuarioDiv = document.createElement("div");
        usuarioDiv.classList.add("usuario-card", "position-relative");
        usuarioDiv.style.minWidth = "250px";

        usuarioDiv.innerHTML = `
        <button type="button" class="btn-close position-absolute top-0 end-0 btn-delete-usuario"></button>

        <!-- Buscador -->
        <input type="text" class="form-control mb-1 buscador-usuario" placeholder="Buscar correo...">

        <!-- Select normal -->
        <select class="form-select" name="usuarios[${fase.id_fase}][]"></select>
    `;

        usuariosContainer.appendChild(usuarioDiv);

        const selectUsuario = usuarioDiv.querySelector(
          `select[name="usuarios[${fase.id_fase}][]"]`
        );

        const buscadorInput = usuarioDiv.querySelector(".buscador-usuario");

        // Cargar correos
        cargarUsuariosEdit(selectUsuario, usuarioId);

        // 🔍 Filtro + selección automática de la primera coincidencia
buscadorInput.addEventListener("input", function () {
    const filtro = this.value.toLowerCase();
    let primeraCoincidencia = null;

    Array.from(selectUsuario.options).forEach((opt) => {
        const correo = opt.textContent.toLowerCase();
        const nombre = (opt.dataset.nombre || "").toLowerCase();

        // Coincide si el filtro aparece en el nombre O en el correo
        const coincide = correo.includes(filtro) || nombre.includes(filtro);

        opt.style.display = coincide ? "block" : "none";

        if (coincide && !primeraCoincidencia && filtro.length > 1) {
            primeraCoincidencia = opt;
        }
    });

    if (primeraCoincidencia) {
        selectUsuario.value = primeraCoincidencia.value;
    } else {
        selectUsuario.value = "";
    }
});


        // Botón eliminar usuario
        usuarioDiv
          .querySelector(".btn-delete-usuario")
          .addEventListener("click", function () {
            usuarioDiv.remove();
          });
      }

      // Agregar usuarios iniciales si existen
      if (fase.usuarios && fase.usuarios.length) {
        fase.usuarios.forEach((usrId) => {
          agregarUsuario(usrId);
        });
      } else {
        // Agregar un select vacío por defecto
        agregarUsuario();
      }

      // Botón para agregar nuevo usuario
      card.querySelector(".btn-add-usuario").addEventListener("click", () => {
        agregarUsuario();
      });

      // Eliminar fase completa
      card
        .querySelector(".btn-delete-fase")
        .addEventListener("click", function () {
          if (
            confirm("¿Está seguro de eliminar esta fase y todos sus usuarios?")
          ) {
            card.remove();
          }
        });
    });
  }

function cargarUsuariosEdit(selectElement, idSeleccionado = null) {
    $.ajax({
        url: `${base_url}/Configuracion/getCorreos`,
        method: "GET",
        dataType: "json",
        success: function (response) {
            let options = '<option value="">Seleccionar usuario...</option>';

            if (response && response.length > 0) {
                response.forEach((usuario) => {
                    const nombreCorreo = `${usuario.nombre} - ${usuario.correo}`;
                    const selected = usuario.id == idSeleccionado ? "selected" : "";
                    options += `
                    <option value="${usuario.id}" data-nombre="${usuario.nombre}">
                        ${usuario.correo}
                    </option>`;
                });
            }

            $(selectElement).html(options);

            if (idSeleccionado) {
                $(selectElement).val(idSeleccionado);
            }
        },
        error: function () {
            alert("Error al cargar los usuarios.");
        },
    });
}


  $(document).on("submit", "#formGrupoCorreos", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const id_grupo = $("#edit_id_grupo").val();

    // Recolectar datos de usuarios por fase
    const usuariosPorFase = {};

    $("#contenedorFasesCorreos .card").each(function () {
      const idFase = $(this).data("idFase");
      const usuarios = [];

      $(this)
        .find(`select[name="usuarios[${idFase}][]"]`)
        .each(function () {
          const usuarioId = $(this).val();
          if (usuarioId && usuarioId !== "") {
            usuarios.push(parseInt(usuarioId));
          }
        });

      if (usuarios.length > 0) {
        usuariosPorFase[idFase] = usuarios;
      }
    });

    // Agregar datos al FormData
    formData.append("usuariosPorFase", JSON.stringify(usuariosPorFase));
    formData.append("id_grupo", id_grupo);

    $("#spinnerGrupo").removeClass("d-none");

    $.ajax({
      url: base_url + "/Configuracion/actualizarGrupoCorreos",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        $("#spinnerGrupo").addClass("d-none");
        if (response.status) {
          Swal.fire({
            icon: "success",
            title: "Grupo guardado correctamente",
            timer: 2000,
            showConfirmButton: false,
          });
          $("#modalGrupoCorreos").modal("hide");
          $("#contenedorFasesCorreos").empty();
          $("#formGrupoCorreos")[0].reset();
          tableGrupoCorreos.ajax.reload(null, false);
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            html: Array.isArray(response.message)
              ? response.message.join("<br>")
              : response.message,
          });
        }
      },
      error: function () {
        $("#spinnerGrupo").addClass("d-none");
        Swal.fire({
          icon: "error",
          title: "Error de conexión",
          text: "Ocurrió un problema al enviar los datos.",
        });
      },
    });
  });

  // no pasarse
});
