<?php
require 'database.php';

session_start();

// Verifica si no hay una sesión de usuario iniciada
if (!isset($_SESSION['user_id'])) {
    // Redirige al usuario a la página de inicio de sesión
    header("Location: index.php");
    exit(); // Finaliza la ejecución del script
}

// Obtener el ID de usuario de la sesión
$user_id = $_SESSION['user_id'];

// Prepara la consulta para buscar en la tabla Pacient
$query_doctor = $conn->prepare('SELECT id, email, nom, cognom FROM Doctor WHERE id = :id');
$query_doctor->bindParam(':id', $user_id);
$query_doctor->execute();
$results_doctor = $query_doctor->fetch(PDO::FETCH_ASSOC);


// Prepara la consulta para buscar en la tabla Doctor
$query_pacient = $conn->prepare('SELECT id, email, nom, cognom FROM Pacient WHERE id = :id');
$query_pacient->bindParam(':id', $user_id);
$query_pacient->execute();
$results_pacient = $query_pacient->fetch(PDO::FETCH_ASSOC);

// Verifica si el usuario es un paciente o un doctor y muestra los resultados correspondientes
if (!empty($results_doctor)) {
    $user_type = 'doctor';
    $results = $results_doctor;
} else {
    // Si no es un paciente, asumimos que es un doctor
    $user_type = 'pacient';
    $results = $results_pacient;
}

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
   <link rel="icon" href="img/logo2.png" type="image/x-icon">
</head>
<body>
    <?php require 'partes/menu.php' ?>
<main>
<div class="container__cover">
    <div class="cover">
        <div class="text">
            <?php if(!empty($results)):?>
                <p>Benvinguda/t, <?= $results['nom'] . ' ' . $results['cognom']?></p>
                <p>Has iniciat sessió com a <?= $results['email']?> Gràcies per utilitzar Salud Connectada.</p>
            <?php endif; ?>
            <input type="button" value="Llegir més">
                <div class="more-text">
                    <p>Salut Connectada és una plataforma web dissenyada per oferir un servei de recordatori personalitzat als seus usuaris en l'àmbit de la salut.</p>
                    <p>La seva funcionalitat principal es basa en la capacitat dels usuaris per programar i rebre recordatoris virtuals relacionats amb la presa de medicaments i cites mèdiques.</p>
                </div>
        </div>
        <div class="image">
            <img src="img/inspo.png" alt="salutconnectada">
        </div>
    </div>
</div>
</div>
</main>
<script src="js/script2.js"></script>

</body>
</html> 
 