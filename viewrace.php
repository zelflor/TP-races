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
        <link rel="stylesheet" href="css/pages/viewRace.css">
        <link rel="shortcut icon" href="../media/favicon.png" type="image/x-icon">
</head>
    <body>
      <?php

        include './components/header.php';
        ?>


    <?php 
                
                 include_once './db/variables.php';
                $idrace = $_GET['id'];
                try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $stmt = $conn->prepare("SELECT * FROM course WHERE $idrace = cou_id");
                $stmt->execute();
                $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($resultats) {
                    foreach ($resultats as $result){
                        ?>
                       
                    <section>
                        <div class="banner-container">
                            <img src="media/montagne.png" alt="">
                            <h1><?php echo $result['cou_ville']; ?></h1>
                        </div>
                    <div class="races-info-container">
                        <h2 class="races-name"><?php echo $result['cou_nom']; ?></h2>
                        <div class="races-btns-container-flex">
                        <a href="participerCourse.php">
                            <button>Participer</button>
                        </a>
                        <a href="ajouterAdherent.php">
                            <button class="btn-admin">Ajouter adhérents manuellement</button>
                        </a>
                        <a href="supprimerCourse.php">
                            <button class="btn-admin">Supprimer la course</button>
                        </a>
                    </div>

                </div>
           
                
        </section>
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
     
       

         <?php

        include './components/footer.php';
        ?>
    </body>
</html>