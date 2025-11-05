document.addEventListener("DOMContentLoaded", function () {
  tableModulos = $("#tableModulos").DataTable({
    ajax: {
      url: base_url + "/Configuracion/getModulos",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },
      { data: "nombre_modulo" },
      { data: "estado" },
      {
        data: null,
        render: function (data, type, row) {
          return `
            <button type="button" class="btn btn-primary edit-btn" 
              data-bs-toggle="modal" 
              data-bs-target="#modalEditarEstados" 
              data-id="${row.id_modulo}">
              <i class="fas fa-edit"></i>
            </button>`;
        },
      },
    ],
  });

  // 📌 Cuando se abre el modal para editar los estados
  $(document).on("click", ".edit-btn", function () {
    const id_modulo = $(this).data("id");
    $("#id_modulo").val(id_modulo);

    // Mostrar mensaje de carga
    $("#contenedorEstados").html(
      "<p class='text-muted'>Cargando estados...</p>"
    );

    // Petición AJAX
    fetch(base_url + "/Configuracion/getEstadosModulo/" + id_modulo)
      .then((res) => res.json())
      .then((data) => {
        if (!data || data.length === 0) {
          $("#contenedorEstados").html(
            "<p class='text-warning'>No hay estados configurados para este módulo.</p>"
          );
          return;
        }

        // Construcción de la tabla
        let html = `
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:70%">Nombre del Estado</th>
                <th class="text-center" style="width:30%">Asignado</th>
              </tr>
            </thead>
            <tbody>
      `;

        data.forEach((estado) => {
          const checked = estado.asignado == 1 ? "checked" : "";
          html += `
          <tr>
            <td>${estado.nombre_estado}</td>
            <td class="text-center">
              <input class="form-check-input" type="checkbox"
                     id="estado_${estado.id_estado}"
                     name="estados[]"
                     value="${estado.id_estado}" ${checked}>
            </td>
          </tr>
        `;
        });

        html += `
            </tbody>
          </table>
        </div>
      `;

        $("#contenedorEstados").html(html);
      })
      .catch(() => {
        $("#contenedorEstados").html(
          "<p class='text-danger'>Error al cargar los estados</p>"
        );
      });
  });

  // 📌 Envío del formulario
  $("#formEditarEstados").submit(function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch(base_url + "/Configuracion/guardarEstadosModulo", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status) {
          Swal.fire("Éxito", data.msg, "success");
          $("#modalEditarEstados").modal("hide");
        } else {
          Swal.fire("Error", data.msg, "error");
        }
      })
      .catch(() => {
        Swal.fire("Error", "No se pudo guardar la configuración", "error");
      });
  });
});
