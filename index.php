<?php
   session_start();
   require 'database.php'; // Importa el archivo que contiene la conexión a la base de datos

   // Variable local para mensajes
   $message = '';

   // Verifica si se ha enviado el formulario de registro
   if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
       // Comprueba si se han enviado todos los campos requeridos
       if (!empty($_POST['nom']) && !empty($_POST['cognom']) && !empty($_POST['email']) && !empty($_POST['contrasenya'])) {
           // Prepara la consulta SQL para insertar un nuevo usuario
           $sql = "INSERT INTO Pacient (nom, cognom, telefon, email, contrasenya) VALUES (:nom, :cognom, :telefon, :email, :contrasenya)";
          
           try {
               // Prepara la consulta SQL
               $stmt = $conn->prepare($sql);
              
               // Vincula los parámetros del formulario con los de la consulta SQL
               $stmt->bindParam(":nom", $_POST["nom"]);
               $stmt->bindParam(":cognom", $_POST["cognom"]);
               $stmt->bindParam(":telefon", $_POST["telefon"]);
               $stmt->bindParam(":email", $_POST["email"]);
              
               // Encripta la contraseña antes de insertarla en la base de datos
               $contrasenya = password_hash($_POST["contrasenya"], PASSWORD_DEFAULT);
               $stmt->bindParam(":contrasenya", $contrasenya);


               // Ejecuta la consulta SQL
               if ($stmt->execute()) {
                   $message = 'El usuario se ha creado correctamente';
               } else {
                   $message = 'Error en la creación del usuario, compruebe si hay algún error';
               }
           } catch (PDOException $e) {
               $message = 'Error en la consulta: ' . $e->getMessage();
           }
       } else {
           $message = 'Todos los campos son obligatorios!';
       }
   }


   // Verifica si se ha enviado el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Comprueba si se han enviado los campos requeridos
    if (!empty($_POST['role']) && !empty($_POST['email']) && !empty($_POST['contrasenya'])) {
        $role = $_POST['role']; // Obtener el valor del desplegable

        // Verificar si el usuario seleccionó "Pacient" o "Doctor"
        if ($role == 'pacient') {
            $table = 'Pacient';
        } else  {
            $table = 'Doctor';
        } 

        // Prepara y ejecuta la consulta para obtener los datos del usuario
        $query = $conn->prepare("SELECT id, email, contrasenya FROM $table WHERE email=:email");
        $query->bindParam(':email', $_POST['email']);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        // Inicializa el mensaje de error
        $message = '';

        // Verifica si se encontraron resultados y si la contraseña es correcta
        if ($results && password_verify($_POST['contrasenya'], $results['contrasenya'])) {
            // Inicia la sesión del usuario
            $_SESSION['user_id'] = $results['id'];
            // Redirige a otra página
            header('Location: firts.php'); // QUANDO SE PASE AL SERVER PONER LA /
            // Finaliza la ejecución del script
            exit();
        } else {
            // Define el mensaje de error si las credenciales son incorrectas
            $message = 'Credenciales incorrectas';
        }
    } else {
        // Define el mensaje de error si no se enviaron todos los campos
        $message = 'Todos los campos son obligatorios!';
    }
}

$_SESSION['correo_usuario'] = $correo_usuario;

?>



<!DOCTYPE html>
<html lang="ca">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Salut Connectada</title>
   <link rel="stylesheet" href="css/first-style.css">
   <link rel="icon" href="img/logo2.png" type="image/x-icon">
</head>
<body>
  <h1 class="title">Mantén-te sempre connectat</h1>
  <p class="logo-text">Salut Connectada</p>
  <div class="container" id="container">
      <div class="form-container sign-up">
          <form action="" method="post">
              <h1>Crear compte</h1>
              <input type="text" name="nom" placeholder="Nom">
              <input type="text" name="cognom" placeholder="Cognom">
              <input type="number" name="telefon" placeholder="Numero de telefon">
              <input type="email" name="email" placeholder="Correu electrònic">
              <input type="password" name="contrasenya" placeholder="Contrasenya">
              <input type="submit" value="Registrar-se" name="signup">
          </form>
            <?php if(!empty($message)): ?>
                <p><?= $message ?></p>
            <?php endif; ?>
      </div>
      <div class="form-container sign-in">
          <form action="" method="post">
              <h1>Iniciar sessió</h1>
              <select name="role">
                <option value="" disabled selected hidden>Selecciona una opció</option>
                <option value="pacient">Pacient</option>
                <option value="doctor">Doctor</option>
             </select>
              <input type="email" name="email" placeholder="Correu electrònic">
              <input type="password" name="contrasenya" placeholder="Contrasenya">
              <input type="submit" value="Iniciar sessió" name="login">
          </form>
      </div>
      <div class="toggle-container">
          <div class="toggle">
              <div class="toggle-panel toggle-left">
                  <h1>Benvingut</h1>
                  <p>Introdueix les teves dades personals per utilitzar totes les funcions del lloc</p>
                  <button class="hidden" id="login">Iniciar sessió</button>
              </div>
              <div class="toggle-panel toggle-right">
                  <h1>Benvingut</h1>
                  <p>Registra't amb les teves dades personals per utilitzar totes les funcions del lloc</p>
                  <button class="hidden" id="register">Registrar-se</button>
              </div>
          </div>
      </div>
  </div>
  <!-- Script per canviar entre el formulari de registre i el d'inici de sessió -->
  <script src="js/script.js"></script>
</body>
</html>
