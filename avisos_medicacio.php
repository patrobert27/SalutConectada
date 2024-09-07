<?php

require 'database.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL
$sql = "SELECT
            Pacient.email AS email,
            Medicament.nombre AS medicament,
            Intervall.nom AS IntervallNom,
            Intervall.id AS intervall_id,
            Medicacio.ultim_avis AS ultim_avis,
            Medicacio.id AS medicacio_id
        FROM
            Medicacio
        INNER JOIN Intervall ON Medicacio.IntervallID = Intervall.ID
        INNER JOIN Pacient ON Medicacio.PacientID = Pacient.id
        INNER JOIN Medicament ON Medicament.id = Medicacio.MedicamentID
        WHERE
            Medicacio.DataInici <= CURDATE()
        AND Medicacio.DataFinal >= CURDATE();";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
$stmt->execute();

// Mostrar los resultados
if ($stmt->rowCount() > 0) {
    echo "<table><tr><th>Email</th><th>Medicament</th><th>Intervalo</th></tr>";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>".$row["email"]."</td><td>".$row["medicament"]."</td><td>".$row["IntervallNom"]."</td></tr>";
        envia_mail($conn, $row["email"], $row["medicament"], $row["IntervallNom"], $row['medicacio_id'], $row['intervall_id'], $row['ultim_avis']);
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

function envia_mail($conn, $email, $medicament, $IntervallNom, $medicacio_id, $intervall_id, $ultim_avis){

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                              //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';         //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                     //Enable SMTP authentication
        $mail->Username   = 'salut.connectada@gmail.com'; //SMTP username
        $mail->Password   = 'dyfvymiwwdgxvoyy';       //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit SSL encryption
        $mail->Port       = 465;                      //TCP port to connect to

        // Recipients
        $mail->setFrom('salut.connectada@gmail.com', 'Salut Connectada'); // Sender Email and name
        $mail->addAddress($email);                 //Add a recipient email  
        $mail->addReplyTo('salut.connectada@gmail.com'); // reply to sender email

        // Content
        $mail->isHTML(true);                        //Set email format to HTML
        $mail->Subject = "Alarma medicacio";    // email subject headings
        $mail->Body    = "Tomate el $medicament que habías de tomar cada $IntervallNom"; //email message

        // Calcular el intervalo de tiempo requerido según el ID del intervalo
        $intervalo_horas = 0;
        switch ($intervall_id) {
            case 1:
                $intervalo_horas = 24;
                break;
            case 2:
                $intervalo_horas = 12;
                break;
            case 3:
                $intervalo_horas = 6;
                break;
            case 4:
                $intervalo_horas = 8;
                break;
            case 5:
                $intervalo_horas = 48;
                break;
            case 6:
                $intervalo_horas = 72;
                break;
            default:
                break;
        }

        // Verificar si han pasado las horas necesarias desde el último aviso
        $ultima_vez = strtotime($ultim_avis);
        $hora_actual = time();
        $diferencia_horas = round(($hora_actual - $ultima_vez) / (60 * 60), 2);

        if ($diferencia_horas >= $intervalo_horas) {
            // Enviar correo electrónico
            $mail->send();
            echo "Correo enviado a $email<br>";

            // Actualizar la columna `ultim_avis` en la base de datos
            $now = date('Y-m-d H:i:s');
            $sqlUpdate = "UPDATE Medicacio SET ultim_avis = :now WHERE id = :medicacio_id";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':now', $now);
            $stmtUpdate->bindParam(':medicacio_id', $medicacio_id);
            $stmtUpdate->execute();
        } else {
            echo "Aún no han pasado las $intervalo_horas horas desde el último aviso para $email<br>";
        }
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}<br>";
    }
}
?>
