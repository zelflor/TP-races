
<?php 
session_start();


$ip_bloquee = '172.16.1.10';
$ip_visiteur = $_SERVER['REMOTE_ADDR'];

if ($ip_visiteur === $ip_bloquee) {
    http_response_code(403);
    die('Accès refusé');
}


if (empty($_SESSION['user'] || $_SESSION['user']['admin'] == '1')){
    header('Location: /');
    exit();
}
include_once './db/variables.php';
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
        <link rel="stylesheet" href="css/pages/Forms.css">
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
                <h2>Ajouter un adhérents a la course "1"</h2>
                <select name="user" id="user">
                     <?php 
                
                 
            
                try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("SELECT 
                    a.adh_licence,
                    a.adh_nom,
                    a.adh_prenom,
                    a.adh_dateNaissance,
                    a.adh_sexe,
                    a.adh_mail,
                    a.adh_avatar,
                    COUNT(i.ins_couId) AS nb_courses
                FROM adherent a
                LEFT JOIN inscrire i ON a.adh_licence = i.ins_adhLicence
                GROUP BY a.adh_licence, a.adh_nom, a.adh_prenom
                ");
                
                $stmt->execute();
                $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($resultats) {
                    foreach ($resultats as $result){
                        ?>
                <option value="<?php echo $result['adh_licence']; ?>"><?php echo $result['adh_nom']; ?> <?php echo $result['adh_prenom']; ?></option>
                        <?php
                    }
                }else {
                    $message ="error";
                }
                
                    
                }
                catch (PDOException $e) {
                $message = "Echec de l'affichage :" . $e->getMessage();
                }

                if (!empty($message)){
                    echo $message;
                }
        
                ?>
                </select>
                <button type="submit">Oui</button>
            </form>
        </section>
         <?php

        include './components/footer.php';
        ?>
    </body>
</html>