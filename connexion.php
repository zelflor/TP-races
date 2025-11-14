<?php 
session_start();

if (!empty($_SESSION['user'])){
    header('Location: /');
    exit();
}
include_once './db/variables.php';


//  if (isset($_POST['login']) && isset($_POST['pwd'])) {
//   $login = $_POST['login'];
//   $pass_hache = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

//   try {
//    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
//    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//    $stmt = $conn->prepare("SELECT uti_pwd FROM utilisateur WHERE uti_login = :login");
//    $stmt->bindParam(':login', $login);
//    $stmt->execute();
//    $resultat = $stmt->fetch();
//    if (!$resultat) {
//     $message = "Mauvais identifiant !";
//    } else {
//     $isPasswordCorrect = password_verify($_POST['pwd'], $resultat['uti_pwd']);
//     if (!$isPasswordCorrect) {
//      $message = "Mauvais mot de passe !";
//     } else {
//      $_SESSION['nom'] = $login;
//      $message = "Connexion effectuée !";
//      echo '<meta http-equiv="refresh" content="1;url=index.php">';
//     }
//    }
//   } catch (PDOException $e) {
//    $message = "Echec de l'affichage :" . $e->getMessage();
//   }
//   $conn = null;
//  }

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $message = '';

    $email_user = $_POST['email'];
    $password_user = $_POST['password'];

    
    if (empty($email_user) || empty($password_user)){
        $message = 'Email ou password pas defini';
        
    }else {
        $password_hash_user = password_hash($password, PASSWORD_DEFAULT);
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
              $stmt = $conn->prepare("SELECT uti_pwd FROM adherent WHERE adh_mail = :email");
                $stmt->bindParam(':email', $email_user);
                $stmt->execute();
                $resultat = $stmt->fetch();
            $message = "Connection reussi!";
            $conn = null;
        } catch (PDOException $e) {
            $message = "Echec de l'affichage :" . $e->getMessage();
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
            <!--  -->
            <form action="/connexion.php" method="post">
                <?php 
                if (!empty($message)){
                    echo '<p> ' . $message . '</p>';
                }
                ?>
                <h2>Hello 👋,De retour pour battre votre <span>record</span> ?</h2>
                <input type="email" name="email" id="email" placeholder="exemple@btsciel.lan">
                <input type="password" name="password" id="password" placeholder="mot de passe">
                <button type="submit">Se connecter</button>
            </form>
        </div>

         <?php

        include './components/footer.php';
        ?>
    </body>
</html>