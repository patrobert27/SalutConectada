const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');


registerBtn.addEventListener('click', () => {
   container.classList.add("active");
});


loginBtn.addEventListener('click', () => {
   container.classList.remove("active");
});


//Solicitar rellenar los campos para entrar a la interfaz


document.addEventListener('DOMContentLoaded', function () {
   const signInButton = document.querySelector('.form-container.sign-in button');
   signInButton.addEventListener('click', function (event) {
       event.preventDefault(); // Evita el comportamiento predeterminado del botón (enviar el formulario)


       // Obtén los valores de los campos de inicio de sesión
       const emailInput = document.querySelector('.form-container.sign-in input[type="email"]');
       const passwordInput = document.querySelector('.form-container.sign-in input[type="password"]');
       const emailValue = emailInput.value.trim();
       const passwordValue = passwordInput.value.trim();


       // Verifica si los campos están completos
       if (emailValue !== '' && passwordValue !== '') {
           // Si los campos están completos, redirige a "interfaz.html"
           window.location.href = 'interfaz.html';
       } else {
           // Si los campos no están completos, muestra un mensaje de error o realiza alguna otra acción
           alert('Por favor, complete todos los campos.');
       }
   });
});

