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
        <div class="div-form-flex-container">
            <!--  -->
            <form action="">
                <h2>Hello 👋,De retour pour battre votre <span>record</span> ?</h2>
                <input type="email" name="email" id="" placeholder="exemple@btsciel.lan">
                <input type="password" name="password" id="" placeholder="mot de passe">
                <button type="submit">Se connecter</button>
            </form>
        </div>

         <?php

        include './components/footer.php';
        ?>
    </body>
</html>