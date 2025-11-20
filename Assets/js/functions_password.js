document.addEventListener("DOMContentLoaded", function () {
  const formActualizar = document.getElementById("formActualizarPassword");
  const tokenForm = document.getElementById("tokenForm");

  // Enviar formulario de cambio de contraseña
  formActualizar.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(formActualizar);

    Swal.fire({
      title: "Procesando...",
      text: "Validando información",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    fetch(base_url + "/Dashboard/actualizarPassword", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        Swal.close();

        if (data.status) {
          // Pasar correo al campo oculto del segundo modal
          document.getElementById("correoToken").value =
            formData.get("correo_empresarial");

          Swal.fire({
            icon: "success",
            title: "Token enviado",
            text: data.msg,
            timer: 2000,
            showConfirmButton: false,
          }).then(() => {
            const codigoModal = new bootstrap.Modal(
              document.getElementById("codigoModal")
            );
            codigoModal.show();
          });
        } else {
          Swal.fire("Error", data.msg, "error");
        }
      })
      .catch((error) => {
        Swal.close();
        Swal.fire("Error", "Ocurrió un error inesperado.", "error");
        console.error("Error:", error);
      });
  });

  // Validar token y actualizar contraseña
  tokenForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const tokenBtn = document.getElementById("tokenSubmitBtn");
    const spinner = document.getElementById("tokenSpinner");
    const correo = document.getElementById("correoToken").value;
    const password = document.getElementById("password_new").value;

    tokenBtn.disabled = true;
    spinner.classList.remove("d-none");

    const formData = new FormData(tokenForm);
    formData.append("password", password);

    fetch(base_url + "/Dashboard/validarToken", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        tokenBtn.disabled = false;
        spinner.classList.add("d-none");

        if (data.status) {
          Swal.fire("Éxito", data.msg, "success");

          // Reset formularios
          formActualizar.reset();
          tokenForm.reset();

          // Cerrar modal
          const modal = bootstrap.Modal.getInstance(
            document.getElementById("codigoModal")
          );
          modal.hide();
        } else {
          Swal.fire("Error", data.msg, "error");
        }
      })
      .catch((error) => {
        tokenBtn.disabled = false;
        spinner.classList.add("d-none");
        Swal.fire("Error", "Error al validar el token.", "error");
        console.error("Error:", error);
      });
  });
});
