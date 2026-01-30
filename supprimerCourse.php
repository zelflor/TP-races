<?php 
session_start();

include_once './db/variables.php';

$ip_bloquee = '172.16.1.10';
$ip_visiteur = $_SERVER['REMOTE_ADDR'];

if ($ip_visiteur === $ip_bloquee) {
    http_response_code(403);
    die('Accès refusé');
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
        <link rel="stylesheet" href="css/pages/Connexion.css">
        <link rel="shortcut icon" href="../media/favicon.png" type="image/x-icon">
        <link rel="stylesheet" href="css/pages/Forms.css">
</head>
    <body>
        <?php

        include './components/header.php';
        ?>


        <section>
            <!--  -->
            <form action="">
                <h2>Sur de voulois supprimer la course <?php echo $result['cou_nom']; ?>?</h2>
                <input type="hidden" name="id" value="<?php echo $result['cou_id']; ?>">
                <button type="submit">Oui</button>
            </form>
        </section>
         <?php

        include './components/footer.php';
        ?>
    </body>
</html>