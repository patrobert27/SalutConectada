<?php
require 'database.php';
use PHPMailer\PHPMailer\PHPMailer;

session_start();
require_once 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php'; 
$user_id = $_SESSION['user_id'];

$message = '';


$query = $conn->prepare('SELECT id, nom FROM Especialitat');
$query->execute();
$especialitats = $query->fetchAll(PDO::FETCH_ASSOC);
$query = $conn->prepare('SELECT id, nom, cognom, IDEspecialitat, email FROM Doctor');
$query->execute();
$doctors = $query->fetchAll(PDO::FETCH_ASSOC);
$queryDoctors = $conn->prepare('SELECT id, nom, cognom FROM Pacient');
$queryDoctors->execute();
$pacients = $queryDoctors->fetchAll(PDO::FETCH_ASSOC);

// Enviar los datos de la consulta
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cita'])) {
    // Comprobar si se han enviado todos los campos requeridos
    if (!empty($_POST['Data']) && !empty($_POST['Hora']) && !empty($_POST['DoctorID']) && !empty($_POST['Motiu'])) {
        // Preparar la consulta SQL para insertar una nueva Cita
        $sql = "INSERT INTO Cita (DoctorID, PacientID, Data, Hora, Motiu) VALUES (:DoctorID, :PacientID, :Data, :Hora, :Motiu)";
     
        try {
            // Preparar la consulta SQL
            $stmt = $conn->prepare($sql);
         
            // Vincular los parámetros del formulario con los de la consulta SQL
            $stmt->bindParam(":DoctorID", $_POST["DoctorID"]);
            $stmt->bindParam(":PacientID", $user_id);
            $stmt->bindParam(":Data", $_POST["Data"]);
            $stmt->bindParam(":Hora", $_POST["Hora"]);
            $stmt->bindParam(":Motiu", $_POST["Motiu"]);     

            // Ejecutar la consulta SQL
            if ($stmt->execute()) {
                $message = 'La cita se ha agendado correctamente.';
                
                // Obtener el correo electrónico del doctor
                $doctorID = $_POST['DoctorID'];
                $correo_doctor = '';
                foreach ($doctors as $doctor) {
                    if ($doctor['id'] == $doctorID) {
                        $correo_doctor = $doctor['email'];
                        break;
                    }
                }

                $pacient_nom = '';
                foreach ($pacients as $pacient_NOM) {
                    if ($pacient_NOM['id'] == $user_id) {
                        $pacient_nom = $pacient_NOM['nom'];
                        break;
                    }
                }

                $pacient_cognom = '';
                foreach ($pacients as $pacient_COGNOM) {
                    if ($pacient_COGNOM['id'] == $user_id) {
                        $pacient_cognom = $pacient_COGNOM['cognom'];
                        break;
                    }
                }
                
                // Verificar si se encontró la dirección de correo electrónico del doctor
                if (!empty($correo_doctor)) {
                    // Configurar PHPMailer y enviar el correo electrónico al doctor
                    $mail = new PHPMailer;
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'salut.connectada@gmail.com';
                    $mail->Password = 'dyfvymiwwdgxvoyy';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                    $mail->setFrom('tu_correo@gmail.com', 'salut.connectada@gmail.com');
                    $mail->addAddress($correo_doctor);
                    $mail->Subject = 'Nova solicitud de Cita';
                    $mail->Body = 'Hola, ' . $doctor['nom'] . ', tens una nova cita amb ' . $pacient_NOM['nom'] . ' ' . $pacient_COGNOM['cognom'] . '. Si us plau, apunta a la agenda el dia ' . $_POST["Data"] . ' a la hora ' . $_POST["Hora"] . '.';
                    
                    // Enviar el correo electrónico
                    if ($mail->send()) {
                        // El correo se envió correctamente
                        $message .= ' ¡El correo se envió correctamente!';
                    } else {
                        // Hubo un error al enviar el correo
                        $message .= ' Error al enviar el correo: ' . $mail->ErrorInfo;
                    }
                } else {
                    $message .= ' No se encontró la dirección de correo electrónico del doctor.';
                }
            } else {
                $message = 'Error al agendar la cita. Por favor, inténtalo nuevamente.';
            }
        } catch (PDOException $e) {
            $message = 'Error en la consulta: ' . $e->getMessage();
        }
    } else {
        $message = 'Todos los campos son obligatorios!';
    }
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Salut Connectada</title>
<link rel="stylesheet" href="css/styles(consulta).css">
<link rel="icon" href="logo.png" type="image/x-icon">
</head>
<body>
<?php require 'partes/menu.php'?>
<div class="supercontainer">
    <div class="container">
        <h1>Consulta</h1>
        <form method="POST">
            <label for="profession">Professional assignat:</label>
            <!-- Llenamos los datos de los doctores -->
            <select name="DoctorID" id="profession">
                <?php foreach ($doctors as $doctor) { ?>
                    <option value="<?php echo $doctor['id']; ?>">
                        <?php echo $doctor['nom'] . ' ' . $doctor['cognom'] . '  ('; ?> 
                        <?php 
                            foreach ($especialitats as $especialitat) {
                                if ($especialitat['id'] == $doctor['IDEspecialitat']) {
                                    echo $especialitat['nom'];
                                    break;
                                }
                            }
                        ?>
                        <?php echo ')'; ?>
                    </option>
                <?php } ?>
            </select>
            <br><br>
            <label for="date">Data:</label>
            <input type="date" id="date" name="Data">
            <br><br>
            <label for="time">Hora:</label>
            <input type="time" id="time" name="Hora">
            <br><br>
            <label for="reason">Motiu de la consulta:</label>
            <br><br>
            <textarea id="reason" name="Motiu" rows="8" cols="50"></textarea>
            <br><br>
            <input type="submit" name="cita" value="Agendar Cita">
            <br>
            <input type="reset" value="Reiniciar">
        </form>
    </div>
</div>

<div class="calendario-consulta-container">
      <div class="calendario-container">
        <iframe src="calendari.php"></iframe>
      </div>
  </div>
</div>
</body>
</html>
