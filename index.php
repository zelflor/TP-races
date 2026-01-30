<?php 
session_start();

include_once './db/variables.php';

$ip_bloquee = '172.16.1.10';
$ip_visiteur = $_SERVER['REMOTE_ADDR'];

if ($ip_visiteur === $ip_bloquee) {
    http_response_code(403);
    die('Accès refusé <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSjntChKonvOPTkzFMrAye40ok7QcLUBexP_g&s" alt="">');

}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
        <meta charset="UTF-8">
        <meta name="keywords" content="Course, Running">
        <meta name="description" content="Devenez adhérents à des courses à pied">
        <meta name="author" content="QUEIROZ Florian">
        <meta name="viewport" content="width=device-width">
        <title>Club de course à pied</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/pages/index.css">
        <link rel="shortcut icon" href="../media/favicon.png" type="image/x-icon">
        
</head>
    <body>
         <?php

        include './components/header.php';
        
        
        
 ?>
        
     

      <div class="div-welcome-message-pos">
            <div class="div-flex-messages">
                <p>Bienvenue</p><p>dans</p><p>votre</p><p>club</p><p>de</p><p class="important">running</p>
            </div>
        </div> 
        <!-- <section>
        <?php 
        
        ?>
        </section> -->
         <?php

        include './components/footer.php';
        ?>
    </body>
</html>