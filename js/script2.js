document.addEventListener("DOMContentLoaded", function() {
    const moreText = document.querySelector(".more-text");
    const readMoreBtn = document.querySelector(".container__cover .text input[type='button']"); // Modificado para especificar el tipo de botón

    readMoreBtn.addEventListener("click", function() {
        // Toggle para mostrar/ocultar el texto adicional
        if (moreText.style.display === "none" || moreText.style.display === "") { // Modificado para tener en cuenta el estado inicial
            moreText.style.display = "block";
            readMoreBtn.value = "Llegir menys"; // Modificado para cambiar el texto del botón cuando se muestra el texto adicional
        } else {
            moreText.style.display = "none";
            readMoreBtn.value = "Llegir més"; // Modificado para cambiar el texto del botón cuando se oculta el texto adicional
        }
    });
});