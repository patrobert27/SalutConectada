<?php
session_start();
require 'database.php';
$user_id = $_SESSION['user_id'];

// Obtener datos de otras tablas si es necesario (como Pacients, Frequencia, etc.)
$queryPacients = $conn->prepare('SELECT id, nom, cognom FROM Pacient');
$queryPacients->execute();
$pacients = $queryPacients->fetchAll(PDO::FETCH_ASSOC);

$query = $conn->prepare('SELECT id, nombre FROM Medicament');
$query->execute();
$medicaments = $query->fetchAll(PDO::FETCH_ASSOC);

$queryFrequencias = $conn->prepare('SELECT id, nom FROM Frequencia');
$queryFrequencias->execute();
$frequencias = $queryFrequencias->fetchAll(PDO::FETCH_ASSOC);

$queryIntervalls = $conn->prepare('SELECT id, nom FROM Intervall');
$queryIntervalls->execute();
$intervalls = $queryIntervalls->fetchAll(PDO::FETCH_ASSOC);

// Enviar datos a la base de datos cuando se envíe el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['medicacio'])) {
    // Comprobar si se han enviado todos los campos requeridos
    if (!empty($_POST['DataInici']) && !empty($_POST['DataFinal'])  && !empty($_POST['Comentari']) && !empty($_POST['FrequenciaID']) && !empty($_POST['MedicamentID']) && !empty($_POST['IntervallID']) && !empty($_POST['PacientID'])) {
        // Preparar la consulta SQL para insertar un nuevo medicamento
        // Preparar la consulta SQL para insertar un nuevo medicamento
    $sql = "INSERT INTO Medicacio (DoctorID, PacientID, MedicamentID, FrequenciaID, IntervallID, DataInici, DataFinal, Comentari) VALUES (:DoctorID, :PacientID, :MedicamentID, :FrequenciaID, :IntervallID, :DataInici, :DataFinal, :Comentari )";

    try {
        // Preparar la consulta SQL
        $stmt = $conn->prepare($sql);
        
        // Vincular los parámetros del formulario con los de la consulta SQL
        $stmt->bindParam(":DoctorID", $user_id);
        $stmt->bindParam(":PacientID", $_POST["PacientID"]);
        $stmt->bindParam(":MedicamentID", $_POST["MedicamentID"]);
        $stmt->bindParam(":DataInici", $_POST["DataInici"]);
        $stmt->bindParam(":DataFinal", $_POST["DataFinal"]);
        $stmt->bindParam(":Comentari", $_POST["Comentari"]);
        $stmt->bindParam(":FrequenciaID", $_POST["FrequenciaID"]);
        $stmt->bindParam(":IntervallID", $_POST["IntervallID"]);

        // Ejecutar la consulta SQL
        if ($stmt->execute()) {
            $message = 'El medicament se ha registrado correctamente en la planificación de medicación.';
        } else {
            $message = 'Error en el registro del medicamento en la planificación de medicación. Comprueba si hay algún error.';
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
<html lang="ca">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Salut Connectada</title>
   <link rel="stylesheet" href="css/styles(interfaz).css">
   <link rel="icon" href="logo.jpg" type="image/x-icon">
</head>
<body>
<?php require 'partes/menu.php' ?>
<div class="medication-planner">
    <h1>Planificació de Medicació</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <!-- Campos del formulario -->
        <div class="form-group">
            <label for="buscarPaciente">Cercar pacient:</label>
            <select name="PacientID" id="buscarPaciente">
                <?php foreach ($pacients as $pacient) { ?>
                    <option value="<?php echo $pacient['id']; ?>">
                        <?php echo $pacient['nom'] . ' ' . $pacient['cognom'] ?> 
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="medicamento">Medicament:</label>
            <select name="MedicamentID" id="buscarMedicamento">
                <?php foreach ($medicaments as $medicament) { ?>
                    <option value="<?php echo $medicament['id']; ?>">
                        <?php echo $medicament['nombre'] ?> 
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="frecuencia">Freqüència:</label>
            <select name="FrequenciaID" id="buscarFrequencia">
                <?php foreach ($frequencias as $frequencia) { ?>
                    <option value="<?php echo $frequencia['id']; ?>">
                        <?php echo $frequencia['nom'] ?> 
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="intervalo">Interval:</label>
            <select name="IntervallID" id="buscarIntervall">
                <?php foreach ($intervalls as $intervall) { ?>
                    <option value="<?php echo $intervall['id']; ?>">
                        <?php echo $intervall['nom'] ?> 
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fechaInicio">Data d'inici:</label>
            <input type="date" id="fechaInicio" name="DataInici">
        </div>
        <div class="form-group">
            <label for="fechaFinal">Data final:</label>
            <input type="date" id="fechaFinal" name="DataFinal">
        </div>
        <div class="form-group">
            <label for="ComentariID">Comentari:</label>
            <input type="text" id="comentari" name="Comentari">
        </div>
        <!-- Botón de envío -->
        <button type="submit" name="medicacio" >Desa</button>
    </form>
</div>
</body>
</html>
