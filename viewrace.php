<?php 
session_start();

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
        <link rel="stylesheet" href="css/pages/viewRace.css">
        <link rel="shortcut icon" href="../media/favicon.png" type="image/x-icon">
</head>
    <body>
      <?php

        include './components/header.php';
        ?>


    <?php 
                
                
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
                        <a href="participerCourse.php?course=<?php echo $result['cou_id']; ?>">
                            <button>Participer</button>
                        </a>
                           <?php
                            if (!empty($_SESSION['user'])){
                                if ($_SESSION['user']['admin'] == '1'){

                                
                                ?>
                                     <a href="ajouterAdherent.php?course=<?php echo $result['cou_id']; ?>">
                                        <button class="btn-admin">Ajouter adhérents manuellement</button>
                                    </a>
                                    <a href="supprimerCourse.php?course=<?php echo $result['cou_id']; ?>">
                                        <button class="btn-admin">Supprimer la course</button>
                                    </a>
                                <?php
                                }
                            }
                            ?>
                      
                    </div>

                </div>
                <br>
                <h2>Participants</h2>
                <br>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Numéro de licence</th>
            <th>Photo de profile</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Sexe</th>
            <th>Date de naissance</th>
            <th>Mail</th>
        </tr>
    </thead>
    <tbody>
        <?php
        try {
            $conn = new PDO(
                "mysql:host=$servername;dbname=$dbname;charset=utf8",
                $username,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $idrace = $_GET['id'] ?? null;

            if (!$idrace) {
                throw new Exception("ID de course manquant");
            }

            $sql = "
                SELECT 
                    a.adh_licence,
                    a.adh_nom,
                    a.adh_prenom,
                    a.adh_dateNaissance,
                    a.adh_sexe,
                    a.adh_mail,
                    a.adh_avatar
                FROM inscrire i
                INNER JOIN adherent a 
                    ON a.adh_licence = i.ins_adhLicence
                WHERE i.ins_couId = :idrace
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idrace', $idrace, PDO::PARAM_INT);
            $stmt->execute();

            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultats) {
                foreach ($resultats as $result) {
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($result['adh_licence']) ?></td>
                        <td>
                            <div class="div-avatar"
                                 style="background-image:url('/uploads/profile_picture/<?= htmlspecialchars($result['adh_avatar']) ?>')">
                            </div>
                        </td>
                        <td><?= htmlspecialchars($result['adh_prenom']) ?></td>
                        <td><?= htmlspecialchars($result['adh_nom']) ?></td>
                        <td><?= htmlspecialchars($result['adh_sexe']) ?></td>
                        <td><?= htmlspecialchars($result['adh_dateNaissance']) ?></td>
                        <td><?= htmlspecialchars($result['adh_mail']) ?></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='7'>Aucun inscrit pour cette course</td></tr>";
            }

        } catch (Exception $e) {
            echo "<tr><td colspan='7'>Erreur : {$e->getMessage()}</td></tr>";
        }
        ?>
    </tbody>
</table>

                
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