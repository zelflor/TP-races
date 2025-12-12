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
    $password_user = $_POST['password'];

    
    if (empty($email_user) || empty($password_user)){
        $message = 'Email ou password pas defini';
        
    }else {

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
                $stmt = $conn->prepare("SELECT * FROM adherent WHERE adh_mail = :email");
                $stmt->bindParam(':email', $email_user);
                $stmt->execute();
                $resultat = $stmt->fetch();
                if ($resultat){
                    
                    if ($resultat['adh_mail'] == $email_user ){
                        // $hashpassword = password_hash($password_user, PASSWORD_DEFAULT);
                        if (password_verify($password_user, $resultat['adh_password'])) {
                            $message = "Mot de passe correct";
                                $_SESSION['user'] = [
                                'id' => $resultat['adh_licence'],
                                'mail' => $resultat['adh_mail'],
                                'nom' => $resultat['adh_nom'], 
                                'prenom' => $resultat['adh_prenom'],
                                'adh_avatar' => $resultat['adh_avatar'],
                                'admin' => $resultat['admin']
                            ];

                            header('Location: /');
                            exit();
                        }else {
                            $message = "Mot de passe ou identifiant incorrect";
                        }
                    }
                    
                        

                    // $message = "Connection reussi!";
                }else {
                    $message = "Aucun utilisateur avec cette email existe";
                }
              
            
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