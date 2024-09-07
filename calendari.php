<?php
   session_start();
   require 'database.php';
   $user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Salut Connectada</title>
<!-- Core Stylesheet -->
<link rel="stylesheet" href="css/evo-calendar.css" />
<!-- Optional Themes -->
<link rel="stylesheet" href="css/evo-calendar.midnight-blue.css" />
<link rel="stylesheet" href="css/evo-calendar.orange-coral.css" />
<link rel="stylesheet" href="css/evo-calendar.royal-navy.css" />
<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="js/evo-calendar.js"></script>
</head>
<body>
<div id="evoCalendar"></div>
</body>
<script>
   myEvents = [
     {
       id: "required-id-1",
       name: "Cita",
       date: "April/30/2024",
       type: "holiday",
       everyYear: true,
       color: "#a57",
     },
   ];
  
   $('#evoCalendar').evoCalendar({
     calendarEvents: myEvents,
     language:'es'
   });
  
</script>
