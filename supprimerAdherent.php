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
        <link rel="stylesheet" href="css/pages/Connexion.css">
        <link rel="stylesheet" href="css/pages/Forms.css">
        <link rel="shortcut icon" href="../media/favicon.png" type="image/x-icon">
</head>
    <body>
        <?php

        include './components/header.php';
        ?>


        <section>
            <!--  -->
            <form action="">
                <h2>Sur de voulois supprimer l'adherent "Prénom + Nom" de la course id "1"?</h2>
                <input type="hidden" name="id" value="1">
                <button type="submit">Oui</button>
            </form>
        </section>
         <?php

        include './components/footer.php';
        ?>
    </body>
</html>