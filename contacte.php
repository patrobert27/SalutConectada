<?php
    session_start();
    require 'database.php';
    $user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salut Connectada</title>
    <script src="https://kit.fontawesome.com/41bcea2ae3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styles(interfaz).css">
</head>

<body>
<?php require 'partes/menu.php'?>
  <div class="container-2">
    <div class="left">
        <form id="contact" action="mail.php" method="post">
        <h3 class="heading">Contacta amb nosaltres</h3>
            <div class="inputBox">
                <input placeholder="Introdueix teu nom" type="text" name="nom" required tabindex="1" autofocus class="name" />
            </div>
            <div class="inputBox">
                <input 
                    type="email" 
                    name="email" 
                    class="email" 
                    placeholder="Introduiex el teu correu electrònic"
                    required
                    tabindex="2"
                />
            </div>
            <div class="inputBox">
                <input 
                    name="subjecte" 
                    class="subjecte" 
                    placeholder="Introduïu el vostre assumpte"
                    required
                    tabindex="4"
                />
            </div>
            <div class="inputBox">
                <textarea 
                    name="comentari" 
                    class="comentari" 
                    placeholder="Com podem ajudar-lo?"
                    required
                    tabindex="5"
                ></textarea>
            </div>
            <button type="submit" name="send" id="contact-submit">Enviar</button>
        </form>
    </div>


    <div class="right">
        <div class="illustration">
            <img src="img/customer.png" alt="">
        </div>
        <div class="contact-info">
            <div class="infoBox">
                <div class="icon">
                    <ion-icon name="location"></ion-icon>
                </div>
                <div class="text">
                    <p>Paseo Jimínez, 5, 06º B</p>
                </div>
            </div>
            <div class="infoBox">
                <div class="icon">
                    <ion-icon name="call"></ion-icon>
                </div>
                <div class="text">
                    <a href="tel:974 621917">974 621917</a>
                </div>
            </div>
            <div class="infoBox">
                <div class="icon">
                    <ion-icon name="mail"></ion-icon>
                </div>
                <div class="text">
                    <a href="mailto:salut.connectada@gmail.com">salut.connectada@gmail.com</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>
