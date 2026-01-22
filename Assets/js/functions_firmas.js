let tableFirmas;
document.addEventListener("DOMContentLoaded", function () {
  let permisosMod = permisos[10] || {
    acceder: 0,
    crear: 0,
    editar: 0,
    eliminar: 0,
  };
  tableFirmas = $("#tableFirmas").DataTable({
    ajax: {
      url: base_url + "/Configuracion/getGrupoFirmas",
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
          return meta.row + 1;
        },
      },
      { data: "nombre_grupo" },
      { data: "nombre_area" },
      { data: "categoria" },
      { data: "total_firmas" },
      { data: "estado_grupo" },
      {
        data: null,
        render: function (data, type, row) {
          let botones = "";

          // Botón Editar
          if (permisosMod.editar == 1) {
            botones += `
            <button type="button" class="btn btn-warning update-btn" data-bs-toggle="modal" data-bs-target="#modalGrupoFirmasEdit" data-id="${row.id_grupo}">
                <i class="fas fa-pencil-square"></i>
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
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },
  });

  if (document.querySelector("#areas")) {
    let ajaxUrl = base_url + "/Configuracion/getSelectArea";
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");

    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#areas").innerHTML = request.responseText;
        $("#areas");
      }
    };
  }

  // ----------------------------
  // Firmantes
  // ----------------------------
  const contenedorFirmantes = document.getElementById("contenedorFirmantes");
  const btnAgregar = document.getElementById("btnAgregarFirmante");

  function cargarUsuarios(selectElement, callback) {
    $.ajax({
      url: `${base_url}/Configuracion/getUsers`,
      method: "GET",
      dataType: "html",
      success: function (responseText) {
        $(selectElement).html(responseText);
        if (callback) callback();
      },
      error: function () {
        alert("Error al cargar los usuarios.");
      },
    });
  }

  function habilitarBusquedaSelect(select) {
    // Creamos un input temporal que simulará la búsqueda
    const input = document.createElement("input");
    input.type = "text";
    input.placeholder = "Buscar...";
    input.classList.add("form-control", "mb-1");

    // Insertamos el input justo antes del select
    select.parentNode.insertBefore(input, select);

    // Guardamos todas las opciones originales
    const opcionesOriginales = Array.from(select.options);

    // Filtramos mientras el usuario escribe
    input.addEventListener("input", () => {
      const filtro = input.value.toLowerCase();
      select.innerHTML = ""; // limpiar select

      const filtradas = opcionesOriginales.filter((opt) =>
        opt.text.toLowerCase().includes(filtro)
      );

      if (filtradas.length === 0) {
        const noOpt = document.createElement("option");
        noOpt.textContent = "Sin resultados";
        noOpt.disabled = true;
        select.appendChild(noOpt);
      } else {
        filtradas.forEach((opt) => select.appendChild(opt.cloneNode(true)));
      }
    });
  }

  const agregarFirma = () => {
    const ordenActual = contenedorFirmantes.children.length + 1;
    const card = document.createElement("div");
    card.classList.add("card", "shadow-sm", "p-3", "position-relative");
    card.style.width = "300px";

    card.innerHTML = `
    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 btnRemoveFirmante">
      <i class="fas fa-times"></i>
    </button>
    <div class="mb-3">
      <label class="form-label small fw-semibold">Usuario</label>
      <div class="buscador-select">
        <select class="form-select" name="usuarios[]" required>
          <option value="">Cargando usuarios...</option>
        </select>
      </div>
    </div>
    <div class="mb-2">
      <label class="form-label small fw-semibold">Nombres</label>
      <input type="text" name="nombres[]" class="form-control" placeholder="Ej. Juan Pérez" required>
    </div>
    <div class="mb-2">
      <label class="form-label small fw-semibold">Cargo</label>
      <input type="text" name="roles[]" class="form-control" placeholder="Ej. Gerente" required>
    </div>
    <div>
      <label class="form-label small fw-semibold">Orden de Firma</label>
      <input type="number" name="orden[]" class="form-control" value="${ordenActual}" readonly>
    </div>
  `;

    contenedorFirmantes.appendChild(card);

    const selectUsuario = card.querySelector('select[name="usuarios[]"]');
    cargarUsuarios(selectUsuario, () => {
      habilitarBusquedaSelect(selectUsuario); // Activa buscador después de cargar
    });
  };

  btnAgregar.addEventListener("click", (e) => {
    e.preventDefault();
    agregarFirma();
  });

  contenedorFirmantes.addEventListener("click", (e) => {
    const btnRemove = e.target.closest(".btnRemoveFirmante");
    if (!btnRemove) return;
    const card = btnRemove.closest(".card");
    if (card === contenedorFirmantes.firstElementChild) return;
    card.remove();
    Array.from(contenedorFirmantes.children).forEach((c, i) => {
      c.querySelector('input[name="orden[]"]').value = i + 1;
    });
  });

  // ----------------------------
  // Enviar formulario
  // ----------------------------
  $(document).on("submit", "#formGrupoFirmas", function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    const firmantes = [];
    $("#contenedorFirmantes .card").each(function () {
      const usuario = $(this).find("select[name='usuarios[]']").val();
      const nombres = $(this).find("input[name='nombres[]']").val();
      const rol = $(this).find("input[name='roles[]']").val();
      const orden = $(this).find("input[name='orden[]']").val();
      firmantes.push({ usuario, nombres, rol, orden });
    });
    formData.append("firmantes", JSON.stringify(firmantes));

    $("#spinnerGrupo").removeClass("d-none");

    $.ajax({
      url: base_url + "/Configuracion/guardarGrupoFirmas",
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
            title: "Grupo de firmas creado",
            timer: 2000,
            showConfirmButton: false,
          });
          $("#modalGrupoFirmas").modal("hide");
          $("#formGrupoFirmas")[0].reset();
          $("#contenedorFirmantes").empty();
          tableFirmas.ajax.reload(null, false);
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

  // ----------------------------
  // EDITAR GRUPO
  // ----------------------------

  function cargarDatosGrupo(id_grupo) {
    $.ajax({
      url: `${base_url}/Configuracion/getGrupoFirmasByID/${id_grupo}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status && response.data && response.data.grupo) {
          const grupo = response.data.grupo;

          // Datos básicos
          $("#id_grupo_edit").val(grupo.id_grupo);
          $("#nombre_grupo_edit").val(grupo.nombre_grupo);
          $("#areas_edit").val(grupo.area_grupo);
          $("#categoria").val(grupo.categoria);
          // Firmas
          const firmas = response.data.firmas || [];
          cargarFirmasGrupo(firmas);

          // Abrir modal
          $("#modalGrupoFirmasEdit").modal("show");
        } else {
          alert(response.message);
        }
      },
      error: function (err) {
        console.error("Error al cargar grupo:", err);
      },
    });
  }

  if (document.querySelector("#areas_edit")) {
    let ajaxUrl = base_url + "/Configuracion/getSelectArea"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#areas_edit").innerHTML = request.responseText;
        $("#areas_edit");
      }
    };
  }

  // ----------------------------
  // Función para cargar firmas de un grupo
  // ----------------------------
  function cargarFirmasGrupo(firmas) {
    const contenedor = $("#contenedorFirmantesEdit");
    contenedor.empty();

    if (firmas.length === 0) return;

    firmas.forEach((firma) => {
      const card = document.createElement("div");
      card.classList.add(
        "card",
        "shadow-sm",
        "p-3",
        "position-relative",
        "mb-2"
      );
      card.style.width = "300px";
      card.dataset.idFirma = firma.id_firma;

      card.innerHTML = `
      <button type="button" class="btn-close position-absolute top-0 end-0 m-2 btn-delete-firma" title="Eliminar firma"></button>

      <div class="mb-3">
        <label class="form-label small fw-semibold">Usuario</label>
        <div class="buscador-select">
          <select class="form-select" name="usuarios[]" required>
            <option value="">Cargando usuarios...</option>
          </select>
        </div>
      </div>

      <div class="mb-2">
        <label class="form-label small fw-semibold">Nombre</label>
        <input type="text" name="nombres[]" class="form-control" value="${firma.nombre_usuario}">
      </div>

      <div class="mb-2">
        <label class="form-label small fw-semibold">Rol / Cargo</label>
        <input type="text" name="roles[]" class="form-control" value="${firma.cargo_usuario}">
      </div>

      <div>
        <label class="form-label small fw-semibold">Orden de Firma</label>
        <input type="number" name="orden[]" class="form-control" value="${firma.orden}" readonly>
      </div>
    `;

      contenedor[0].appendChild(card);

      const selectUsuario = card.querySelector('select[name="usuarios[]"]');
      cargarUsuariosEdit(selectUsuario, firma.id_usuario, () => {
        habilitarBusquedaSelect(selectUsuario);
      });
    });
  }

  // ----------------------------
  // Evento click en botón editar
  // ----------------------------
  $(document).on("click", ".update-btn", function () {
    const id_grupo = $(this).data("id");
    cargarDatosGrupo(id_grupo);
  });

  function cargarUsuariosEdit(selectElement, idSeleccionado = null, callback) {
    $.ajax({
      url: `${base_url}/Configuracion/getUsers`,
      method: "GET",
      dataType: "html",
      success: function (responseText) {
        $(selectElement).html(responseText);
        if (idSeleccionado) $(selectElement).val(idSeleccionado);
        if (callback) callback();
      },
      error: function () {
        alert("Error al cargar los usuarios.");
      },
    });
  }

  // ----------------------------
  // Agregar / Eliminar firmas en edición
  // ----------------------------
  const btnAgregarEdit = document.getElementById("btnAgregarFirmanteEdit");
  const contenedorFirmantesEdit = document.getElementById(
    "contenedorFirmantesEdit"
  );

  const agregarFirmaEdit = () => {
    const card = document.createElement("div");
    card.classList.add("card", "shadow-sm", "p-3", "position-relative", "mb-2");
    card.style.width = "300px";

    card.innerHTML = `
    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 btnRemoveFirmante">
      <i class="fas fa-times"></i>
    </button>
    <div class="mb-3 buscador-select">
      <label class="form-label small fw-semibold">Usuario</label>
      <select class="form-select" name="usuarios[]" required>
        <option value="">Cargando usuarios...</option>
      </select>
    </div>
    <div class="mb-2">
      <label class="form-label small fw-semibold">Nombres</label>
      <input type="text" name="nombres[]" class="form-control" placeholder="Ing. Nombre" required>
    </div>
    <div class="mb-2">
      <label class="form-label small fw-semibold">Rol / Cargo</label>
      <input type="text" name="roles[]" class="form-control" placeholder="Ej: Gerente" required>
    </div>
    <div>
      <label class="form-label small fw-semibold">Orden de Firma</label>
      <input type="number" name="orden[]" class="form-control" readonly>
    </div>
  `;

    contenedorFirmantesEdit.appendChild(card);

    const selectUsuario = card.querySelector('select[name="usuarios[]"]');
    cargarUsuarios(selectUsuario, () => {
      habilitarBusquedaSelect(selectUsuario);
    });

    recalcularOrdenFirmas();
  };

  function recalcularOrdenFirmas() {
    Array.from(
      document.querySelectorAll("#contenedorFirmantesEdit .card")
    ).forEach((c, i) => {
      c.querySelector('input[name="orden[]"]').value = i + 2;
    });
  }

  contenedorFirmantesEdit.addEventListener("click", (e) => {
    const btnRemove = e.target.closest(".btnRemoveFirmante");
    if (!btnRemove) return;
    const card = btnRemove.closest(".card");
    card.remove();
    recalcularOrdenFirmas();
  });

  btnAgregarEdit.addEventListener("click", (e) => {
    e.preventDefault();
    agregarFirmaEdit();
  });

  $(document).on("click", ".btn-delete-firma", function () {
    const card = $(this).closest(".card");
    const idFirma = card.data("id-firma");

    if (confirm("¿Desea eliminar esta firma?")) {+
      card.addClass("border-danger opacity-50");
      if (idFirma) {
        const eliminadasInput = $("#firmas_eliminadas");
        let ids = eliminadasInput.val()
          ? JSON.parse(eliminadasInput.val())
          : [];
        if (!ids.includes(idFirma)) ids.push(idFirma);
        eliminadasInput.val(JSON.stringify(ids));
      }
      card.remove();
      recalcularOrdenFirmas();
    }
  });

  // ----------------------------
  // Enviar formulario edición
  // ----------------------------
  $(document).on("submit", "#formGrupoFirmasEdit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    const firmantes = [];
    $("#contenedorFirmantesEdit .card").each(function () {
      const usuario = $(this).find("select[name='usuarios[]']").val();
      const nombres = $(this).find("input[name='nombres[]']").val();
      const rol = $(this).find("input[name='roles[]']").val();
      const orden = $(this).find("input[name='orden[]']").val();
      const idFirma = $(this).data("id-firma") || null;
      firmantes.push({ idFirma, usuario, nombres, rol, orden });
    });
    formData.append("firmantes", JSON.stringify(firmantes));

    const eliminadas = $("#firmas_eliminadas").val() || "[]";
    formData.set("firmas_eliminadas", eliminadas);

    $("#spinnerGrupo").removeClass("d-none");

    $.ajax({
      url: base_url + "/Configuracion/actualizarGrupoFirmas",
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
            title: "Grupo de firmas actualizado",
            timer: 2000,
            showConfirmButton: false,
          });
          $("#modalGrupoFirmasEdit").modal("hide");
          $("#contenedorFirmantesEdit").empty();
          $("#firmas_eliminadas").val("[]");
          tableFirmas.ajax.reload(null, false);
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
});
