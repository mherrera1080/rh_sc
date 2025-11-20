document.addEventListener("DOMContentLoaded", function () {
  const modalEspera = new bootstrap.Modal(document.getElementById("modalEspera"));
  const formLogin = document.getElementById("formLogin");
  const tokenForm = document.getElementById("tokenForm");

  const correoInput = document.getElementById("correoToken");
  const tokenInput = document.getElementById("token");
  const btnValidar = document.querySelector("#tokenSubmitBtn");
  const infoSubmitBtn = document.getElementById("infoSubmitBtn");

  const infoSpinner = document.querySelector("#infoSpinner");
  const tokenSpinner = document.querySelector("#tokenSpinner");

  // --- Enviar credenciales de login ---
  if (formLogin) {
    formLogin.onsubmit = async function (e) {
      e.preventDefault();

      infoSubmitBtn.disabled = true;
      infoSpinner.classList.remove("d-none");

      const correo_empresarial = document.querySelector("#correo_empresarial").value.trim();
      const password = document.querySelector("#password").value.trim();

      if (correo_empresarial === "" || password === "") {
        Swal.fire("Por favor", "Escribe correo y contraseña.", "error");
        infoSubmitBtn.disabled = false;
        infoSpinner.classList.add("d-none");
        return;
      }

      try {
        const response = await fetch(base_url + "/Login/loginUser", {
          method: "POST",
          body: new FormData(formLogin),
        });

        const objData = await response.json();

        if (objData.status) {
          correoInput.value = correo_empresarial; // Guardar correo para validación de token
          modalEspera.show();
          Swal.fire({
            icon: "info",
            title: "Código enviado",
            text: objData.msg,
            timer: 2500,
            showConfirmButton: false,
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: objData.msg || "Error en el inicio de sesión",
          });
        }
      } catch (error) {
        Swal.fire("Atención", "Error en el proceso de inicio de sesión.", "error");
      } finally {
        infoSubmitBtn.disabled = false;
        infoSpinner.classList.add("d-none");
      }
    };
  }

  // --- Validar token ---
  if (tokenForm) {
    tokenForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      btnValidar.disabled = true;
      tokenSpinner.classList.remove("d-none");

      try {
        const response = await fetch(base_url + "/Login/verificarToken", {
          method: "POST",
          body: new FormData(tokenForm),
          headers: { Accept: "application/json" },
        });

        const data = await response.json();

        if (data.status) {
          Swal.fire({
            icon: "success",
            title: "Acceso permitido",
            text: data.msg,
            timer: 2000,
            showConfirmButton: false,
          }).then(() => {
            modalEspera.hide();
            window.location.href = data.redirect;
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Código incorrecto",
            text: data.msg,
          });
        }
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Error de conexión",
          text: "No se pudo validar el token. Intenta de nuevo.",
        });
      } finally {
        btnValidar.disabled = false;
        tokenSpinner.classList.add("d-none");
      }
    });
  }

  // --- Mostrar / Ocultar contraseña ---
  const togglePassword = document.querySelector("#togglePassword");
  const password = document.querySelector("#password");

  if (togglePassword && password) {
    togglePassword.addEventListener("click", function () {
      const type = password.getAttribute("type") === "password" ? "text" : "password";
      password.setAttribute("type", type);
      this.classList.toggle("fa-eye-slash");
    });
  }
});
