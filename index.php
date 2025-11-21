<?php 
session_start();
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
        include_once './db/variables.php';
        
        
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