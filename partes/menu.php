<?php

function es_doctor($user_id, $conn) {
    $query = $conn->prepare('SELECT COUNT(*) FROM Doctor WHERE id = :user_id');
    $query->bindParam(':user_id', $user_id);
    $query->execute();

    
    $result = $query->fetchColumn();


    return $result > 0;
}

if (es_doctor($user_id, $conn)) {
?>
<header class="header">
    <div class="logo">
                <img src="img/logo2.png" alt="logo">
            </div>
            <nav>
            <ul class="nav-links">
                        <a href="firts.php">Inici</a>
                        <a href="medicacio.php">Medicació</a>
                        <a href="contacte.php">Contacte</a>
                </ul>
                </nav>
                <div class="icons">
                    <ul>
                        <li>
                            <form action="logout.php" method="POST">
                                <button type="submit" name="logout">
                                    <ion-icon name="exit-outline"></ion-icon>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                <a onclick="openNav()" class="menu" href="#"><button>Menu</button></a>
                <div id="mobile-menu" class="overlay">
                        <a onclick="closeNav()" href="" class="close">&times;</a>
                        <div class="overlay-content">
                        <a href="firts.php">Inici</a>
                        <a href="medicacio.php">Medicació</a>
                        <a href="contacte.php">Contacte</a>                 
                </div> 
    </header>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script type="text/javascript" src="js/nav.js"></script>
<?php
} else {
?>
<header class="header">
    <div class="logo">
                <img src="img/logo2.png" alt="logo">
                </div>
                <nav>
                <ul class="nav-links">
               
                        <a href="firts.php">Inici</a>
                        <a href="consulta.php">Consulta</a>
                        <a href="contacte.php">Contacte</a>
                </ul>
                </nav>
                <div class="icons">
                    <ul>
                        <li>
                            <form action="logout.php" method="POST">
                                <button type="submit" name="logout">
                                    <ion-icon name="exit-outline"></ion-icon>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                <a onclick="openNav()" class="menu" href="#"><button>Menu</button></a>
                <div id="mobile-menu" class="overlay">
                        <a onclick="closeNav()" href="" class="close">&times;</a>
                        <div class="overlay-content">
                        <a href="firts.php">Inici</a>
                        <a href="consulta.php">Consulta</a>
                        <a href="contacte.php">Contacte</a>
                    </div>
                </div> 
    </header>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script type="text/javascript" src="js/nav.js"></script>
<?php
}

?>
