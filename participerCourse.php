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
        <link rel="shortcut icon" href="../media/favicon.png" type="image/x-icon">
</head>
    <body>
        <?php

        include './components/header.php';
        ?>


        <section>
            <!--  -->
            <form action="">
                <img class="img-avatar" src="./media/default_avatar.png" alt="">
                <hr>
                <h2>Participer à la course : "Montagne" (1)</h2>
                <button type="submit">Oui</button>
            </form>
        </section>
         <?php

        include './components/footer.php';
        ?>
    </body>
</html>