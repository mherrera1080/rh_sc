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
        title: "#",
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { title: "Area", data: "nombre_area" },
      { title: "Estado", data: "estado" },
      {
        data: null,
        title: "Acciones",
        render: function (data, type, row) {
          let botones = "";

          // Botón Editar
          if (permisosMod.editar == 1) {
            botones += `
            <button type="button" class="btn btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#modalGrupoCorreos" data-id="${row.id_area}">
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

  $(document).on("click", ".edit-btn", function () {
    const idEmpresa = $(this).data("id");

    $.ajax({
      url: `${base_url}/Configuracion/getGruposbyArea/${idEmpresa}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#id_area").val(response.data.area);
          $("#nombre_area").val(response.data.nombre_area);
          $("#anticipo").val(response.data.anticipo);
          $("#contraseña").val(response.data.contraseña);
          $("#solicitud").val(response.data.solicitud);

          // Cargar la primera pestaña al inicio
          cargarDatos("1");
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

  document.querySelectorAll("#tipoTabs .nav-link").forEach((btn) => {
    btn.addEventListener("click", function () {
      const categoria = this.dataset.type; // CONTRASEÑA | SOLICITUD | ANTICIPO
      cargarDatos(categoria);
    });
  });
});

function cargarDatos(tipo) {
  return Promise.all([cargarFases(tipo), cargarUsuarios(tipo)])
    .then(() => {
      prepararDragUsuarios();
      prepararDropFases();
    })
    .catch((err) => console.error("Error cargando datos:", err));
}

function crearChipUsuarioAsignado(usuario) {
  const chip = document.createElement("div");
  chip.classList.add("usuario-chip");
  chip.dataset.id = usuario.id;

  chip.innerHTML = `
    <span class="chip-nombre">${usuario.nombre}</span>
    <button type="button" class="chip-remove">&times;</button>
  `;

  // Agregamos el listener al botón de eliminar, llamando a la función independiente
  chip.querySelector(".chip-remove").addEventListener("click", () => {
    eliminarChipUsuario(chip);
  });

  return chip;
}

function eliminarChipUsuario(chip) {
  const zone = chip.parentElement;
  const usuarioId = chip.dataset.id;

  const tipo = document.querySelector("#tipoTabs .nav-link.active").dataset
    .type;

  // 🔥 Asignar grupo según tipo
  let id_grupo = null;
  switch (tipo) {
    case "1":
      id_grupo = document.querySelector("#contraseña").value;
      break;
    case "2":
      id_grupo = document.querySelector("#solicitud").value;
      break;
    case "3":
      id_grupo = document.querySelector("#anticipo").value;
      break;
  }

  const idFase = zone.id.replace("fase-", "");
  const grupo = id_grupo;

  // ⚠ eliminar visual primero
  chip.remove();
  actualizarMensajeFase(zone);

  // 🔥 eliminar en BD
  fetch(
    `${base_url}/Configuracion/removeUsuarioFase/${grupo},${idFase},${usuarioId}`
  )
    .then((r) => r.json())
    .then((res) => {
      if (!res.status) {
        Swal.fire("Error", res.msg, "error");
      }
    });
}

function prepararDragUsuarios() {
  document.querySelectorAll(".usuario-item").forEach((item) => {
    // remover listeners previos seguros
    item.draggable = true;
    item.removeEventListener("dragstart", item._dragstartHandler);
    const handler = function (e) {
      // pasar un JSON con id, nombre, correo
      const obj = {
        id: item.dataset.id,
        nombre:
          item.dataset.nombre ||
          item.querySelector(".nombre")?.textContent?.trim() ||
          item.textContent.trim(),
        correo:
          item.dataset.correo ||
          item.querySelector(".correo")?.textContent?.trim() ||
          "",
      };
      e.dataTransfer.setData("application/json", JSON.stringify(obj));
      // para que el efecto sea copiar (no mover)
      try {
        e.dataTransfer.effectAllowed = "copy";
      } catch (e) {}
    };
    item.addEventListener("dragstart", handler);
    item._dragstartHandler = handler;
  });
}

function actualizarMensajeFase(zone) {
  const tieneUsuarios = zone.querySelectorAll(".usuario-chip").length > 0;

  let msg = zone.querySelector(".msg-sin-usuarios");

  if (!tieneUsuarios) {
    if (!msg) {
      msg = document.createElement("div");
      msg.classList.add("text-muted", "msg-sin-usuarios");
      msg.textContent = "Sin usuarios asignados.";
      zone.appendChild(msg);
    }
  } else {
    if (msg) msg.remove();
  }
}

function prepararDropFases() {
  document.querySelectorAll(".usuarios-asignados").forEach((zone) => {
    // limpiar handlers previos
    zone.removeEventListener("dragover", zone._dragover);
    zone.removeEventListener("dragleave", zone._dragleave);
    zone.removeEventListener("drop", zone._drop);

    const onDragOver = function (e) {
      e.preventDefault();
      zone.classList.add("drop-hover");
    };
    const onDragLeave = function (e) {
      zone.classList.remove("drop-hover");
    };

    const onDrop = function (e) {
      e.preventDefault();
      zone.classList.remove("drop-hover");

      const payload = e.dataTransfer.getData("application/json");
      if (!payload) return;

      const obj = JSON.parse(payload);
      const usuarioId = String(obj.id);

      // evitar duplicados
      if (zone.querySelector(`.usuario-chip[data-id="${usuarioId}"]`)) {
        return;
      }

      // Crear chip visual
      const chip = crearChipUsuarioAsignado({
        id: usuarioId,
        nombre: obj.nombre,
        correo: obj.correo,
      });

      zone.appendChild(chip);
      actualizarMensajeFase(zone);

      // 🔥 Obtener el tipo de la pestaña activa
      const tipo = document.querySelector("#tipoTabs .nav-link.active").dataset
        .type;

      // 🔥 Asignar grupo según tipo
      let id_grupo = null;
      switch (tipo) {
        case "1":
          id_grupo = document.querySelector("#contraseña").value;
          break;

        case "2":
          id_grupo = document.querySelector("#solicitud").value;
          break;

        case "3":
          id_grupo = document.querySelector("#anticipo").value;
          break;
      }

      const idFase = zone.id.replace("fase-", "");
      const grupo = id_grupo;

      fetch(
        `${base_url}/Configuracion/addUsuarioFase/${grupo},${idFase},${usuarioId}`
      )
        .then((r) => r.json())
        .then((res) => {
          if (!res.status) {
            Swal.fire("Error", res.msg, "error");
            chip.remove();
            actualizarMensajeFase(zone);
          }
        });
    };

    zone.addEventListener("dragover", onDragOver);
    zone.addEventListener("dragleave", onDragLeave);
    zone.addEventListener("drop", onDrop);

    zone._dragover = onDragOver;
    zone._dragleave = onDragLeave;
    zone._drop = onDrop;
  });
}

function cargarFases(tipo) {
  let contenedor;
  let categoria;
  let grupo;

  switch (tipo) {
    case "1":
      contenedor = "#contenedorFasesContraseña";
      categoria = 1;
      grupo = document.querySelector("#contraseña").value;
      break;

    case "2":
      contenedor = "#contenedorFasesSolicitud";
      categoria = 2;
      grupo = document.querySelector("#solicitud").value;
      break;

    case "3":
      contenedor = "#contenedorFasesAnticipo";
      categoria = 3;
      grupo = document.querySelector("#anticipo").value;
      break;
  }

  return fetch(base_url + "/Configuracion/getFases/" + tipo)
    .then((r) => r.json())
    .then((data) => {
      let html = "";

      if (data.length === 0) {
        html = `<p class="text-muted">No hay fases registradas.</p>`;
        document.querySelector(contenedor).innerHTML = html;
        return;
      }

      data.forEach((f) => {
        html += `
          <div class="fase-item mb-3 p-3 border rounded" data-id="${f.id_fase}">
              <strong class="titulo-fase">${f.nombre_base}</strong>

              <div class="usuarios-asignados mt-3 p-3 bg-light border rounded"
                  id="fase-${f.id_fase}">
                  <div class="text-muted small">Cargando usuarios...</div>
              </div>
          </div>`;
      });

      document.querySelector(contenedor).innerHTML = html;

      data.forEach((f) => {
        cargarUsuariosFase(grupo, f.id_fase);
      });
    });
}

function cargarUsuariosFase(grupo, idFase) {
  fetch(`${base_url}/Configuracion/getUsuariosByGrupoYFase/${grupo},${idFase}`)
    .then((r) => r.json())
    .then((data) => {
      const target = document.querySelector(`#fase-${idFase}`);
      target.innerHTML = ""; // Limpiar antes de agregar

      if (data.length === 0) {
        target.innerHTML = `<div class="text-muted msg-sin-usuarios">Sin usuarios asignados.</div>`;
      } else {
        data.forEach((u) => {
          // Crear el chip usando la función existente
          const chip = crearChipUsuarioAsignado({
            id: u.usuario,
            nombre: u.nombre_completo,
          });
          target.appendChild(chip);
        });
      }

      actualizarMensajeFase(target);
    });
}


function cargarUsuarios(tipo) {
  let contenedor;
  switch (String(tipo)) {
    case "1":
      contenedor = "#contenedorUsuariosContraseña";
      break;
    case "2":
      contenedor = "#contenedorUsuariosSolicitud";
      break;
    case "3":
      contenedor = "#contenedorUsuariosAnticipo";
      break;
    default:
      contenedor = "#contenedorUsuariosContraseña";
  }

  return fetch(base_url + "/Configuracion/getUsuarios")
    .then((r) => r.json())
    .then((data) => {
      let html = "";
      if (!data || data.length === 0) {
        html = `<div class="text-muted">No hay usuarios</div>`;
      } else {
        data.forEach((u) => {
          // Asegúrate que el backend devuelve id, nombre_completo, correo
          const nombre =
            u.nombre_completo ||
            (u.nombres
              ? u.nombres + " " + (u.primer_apellido || "")
              : u.nombre || "");
          const correo = u.correo || u.correo_empresarial || "";
          html += `<div class="usuario-item p-2 mb-2 border bg-white rounded" draggable="true"
                       data-id="${u.id || u.id_usuario || u.id_persona}"
                       data-nombre="${nombre}"
                       data-correo="${correo}">
                      <div class="nombre">${nombre}</div>
                      <div class="correo small text-muted">${correo}</div>
                   </div>`;
        });
      }

      document.querySelector(contenedor).innerHTML = html;

      // preparar drag events en los items
      prepararDragUsuarios();

      return data;
    });
}
