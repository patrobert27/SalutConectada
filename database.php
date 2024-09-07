<?php
    $server = 'localhost';
    $username = 'alumne'; // EN UN FUTURO SE CAMBIA
    $password = 'alumne';
    $database = 'salut_connectada'; 

    try {
        $conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
        // Configurar PDO para que lance excepciones en caso de error
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Ha fallado: '.$e->getMessage());  
    }
?>
