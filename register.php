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
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
    <body>
         <?php

        include './components/header.php';
        ?>



        <section>
            <!--  -->
            <form action="">
                <h2>Bienvenue 👋,Prêt a commencer une course?</h2>
                <input type="email" name="email" id="" placeholder="exemple@btsciel.lan">
                <input type="text" name="prenom" id="" placeholder="Prénom">
                <input type="date" name="birth" id="" placeholder="Date de naissance">
                <input type="password" name="password" id="" placeholder="mot de passe">
                <button type="submit">S'incrire</button>
            </form>
        </section>
        <footer>
        <p>&copy; 2025 QUEIROZ Florian</p>
        </footer>
    </body>
</html>