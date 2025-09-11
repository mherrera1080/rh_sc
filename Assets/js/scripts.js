window.addEventListener("DOMContentLoaded", (event) => {
    // Selecciona los elementos de la barra lateral y el botón
    const hamBurger = document.querySelector(".toggle-btn"); // Botón que alterna la barra lateral
    const sidebar = document.querySelector("#sidebar"); // La barra lateral
  
    // Persistir el estado de la barra lateral entre refrescos
    if (localStorage.getItem("sb|sidebar-toggle") === "true") {
      sidebar.classList.add("expand"); // Si el estado guardado es true, añade la clase 'expand'
    }
  
    // Verifica si el botón de la barra lateral existe
    if (hamBurger) {
      hamBurger.addEventListener("click", (event) => {
        event.preventDefault(); // Previene el comportamiento por defecto del botón
  
        // Alterna la clase 'expand' en la barra lateral
        sidebar.classList.toggle("expand");
  
        // Guarda el estado de la barra lateral en localStorage
        localStorage.setItem(
          "sb|sidebar-toggle",
          sidebar.classList.contains("expand")
        );
      });
    }
  });

  
  