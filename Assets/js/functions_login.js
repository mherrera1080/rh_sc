document.addEventListener("DOMContentLoaded", function () {
  const formLogin = document.getElementById("formLogin");
  const infoSubmitBtn = document.getElementById("infoSubmitBtn");
  const infoSpinner = document.querySelector("#infoSpinner");

  if (formLogin) {
    formLogin.onsubmit = async function (e) {
      e.preventDefault();

      infoSubmitBtn.disabled = true;
      infoSpinner.classList.remove("d-none");

      let correo_empresarial = document.querySelector("#correo_empresarial").value;

      if (correo_empresarial === "") {
        Swal.fire("Por favor", "Escribe tu correo empresarial.", "error");
        infoSubmitBtn.disabled = false;
        infoSpinner.classList.add("d-none");
        return false;
      }

      try {
        const response = await fetch(base_url + "/Login/loginUser", {
          method: "POST",
          body: new FormData(formLogin),
        });

        const objData = await response.json();

        if (objData.status) {
          Swal.fire({
            icon: "success",
            title: "Bienvenido",
            text: objData.msg,
            timer: 1500,
            showConfirmButton: false,
          }).then(() => {
            window.location.href = base_url + "/dashboard"; // 🔥 Redirigir al dashboard
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: objData.msg || "Error en el inicio de sesión",
          });
          infoSubmitBtn.disabled = false;
          infoSpinner.classList.add("d-none");
        }
      } catch (error) {
        Swal.fire("Atención", "Error en el proceso", "error");
        infoSubmitBtn.disabled = false;
        infoSpinner.classList.add("d-none");
      }
    };
  }
});
