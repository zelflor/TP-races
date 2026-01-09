<?php 
session_start();


if (empty($_SESSION['user'])){
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
        <link rel="stylesheet" href="css/pages/Connexion.css">
        <link rel="shortcut icon" href="../media/favicon.png" type="image/x-icon">
        <link rel="stylesheet" href="css/pages/Forms.css">
</head>
    <body>
        <?php
        include './components/header.php';
        
        $idrace = $_GET['course'];
        ?>


        <section>
            <!--  -->
            <form action="/participerCourse.php?course=<?php echo $idrace ?>">
                <img class="img-avatar" src="/uploads/profile_picture/<?php echo $_SESSION['user']['adh_avatar']; ?>" alt="">
                <hr>
                <?php
                
                
                try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $stmt = $conn->prepare("SELECT * FROM course WHERE $idrace = cou_id");
                $stmt->execute();
                $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($resultats) {
                    foreach ($resultats as $result){
                        ?>
                       
                
                <h2>Participer à la course : "<?php echo $result['cou_nom']; ?>" (<?php echo $result['cou_id']; ?>)</h2>
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
                <input type="hidden" name="course" value="<?php echo $idrace ?>">
                <button type="submit">Oui</button>
            </form>
        </section>
         <?php

        include './components/footer.php';
        ?>
    </body>
</html>