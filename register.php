<?php
session_start();

?>
<?php
if (!empty($_SESSION['user'])){
    header('Location: /');
    exit();
}
include_once './db/variables.php';



if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $message = '';

    $email_user = $_POST['email'];
    $prenom_user = $_POST['prenom'];
    $birth_user = $_POST['birth'];
    $password_user = $_POST['password'];

    if (empty($email_user) || empty($prenom_user) || empty($birth_user) || empty($password_user)){
        $message = "Les champs ne sont pas optionnel";
    } else {

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT adh_mail FROM adherent WHERE adh_mail = :email");
            $stmt->bindParam(':email', $email_user);
            $stmt->execute();
            $resultat = $stmt->fetch();

            if ($resultat){
                $message = "Cet utilisateur existe déjà";
            } else {

                $hashed_password = password_hash($password_user, PASSWORD_DEFAULT);

                $stmtregister = $conn->prepare("
                    INSERT INTO adherent 
                    (adh_prenom, adh_dateNaissance, adh_mail, adh_password)
                    VALUES 
                    (:prenom, :birth, :email, :pass)
                ");

                $stmtregister->bindParam(':prenom', $prenom_user);
                $stmtregister->bindParam(':birth', $birth_user);
                $stmtregister->bindParam(':email', $email_user);
                $stmtregister->bindParam(':pass', $hashed_password);

                $stmtregister->execute();

                $message = "Compte créé avec succès";
            }

            $conn = null;

        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    }
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
</head>
    <body>
         <?php

        include './components/header.php';
        ?>


        <div class="div-form-flex-container">


            <form action="/register.php" method="post">
                <?php 
                if (!empty($message)){
                    echo '<p> ' . $message . '</p>';
                }
                ?>
                <h2>Bienvenue 👋,Prêt a commencer une <span>course</span>?</h2>
                <input type="email" name="email" id="email" placeholder="exemple@btsciel.lan">
                <input type="text" name="prenom" id="prenom" placeholder="Prénom">
                <input type="date" name="birth" id="birth" placeholder="Date de naissance">
                <input type="password" name="password" id="password" placeholder="mot de passe">
                <button type="submit">S'incrire</button>
            </form>
        </div>

         <?php

        include './components/footer.php';
        ?>
    </body>
</html>