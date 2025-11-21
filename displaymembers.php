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
        <link rel="stylesheet" href="css/pages/displayMembers.css">
        <link rel="shortcut icon" href="../media/favicon.png" type="image/x-icon">
</head>
    <body>
         <?php

        include './components/header.php';
        ?>



        <section>
            <div class="div-page-title-container">
                <h3>Liste des Adhérents</h3>
                <div class="div-input-search">
                
                    <input type="text" name="" id="input-text-search" placeholder="Rechercher un adhérents">
                    <button id="btn-submit-search"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21.0002 21L16.7002 16.7M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg></button>
                </div>
            </div>
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
                
                 include_once './db/variables.php';
            
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
                <tr>
                    <td><?php echo $result['adh_licence']; ?></td>
                    <td><div class="div-avatar"></div></td>
                    <td><?php echo $result['adh_prenom']; ?></td>
                    <td><?php echo $result['adh_nom']; ?></td>
                    <td><?php echo $result['adh_sexe']; ?></td>
                    <td><?php echo $result['adh_dateNaissance']; ?></td>
                    <td><?php echo $result['adh_mail']; ?></td>
                </tr>
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
              
            </tbody>
        </table>
        </section>
        <script src="./js/searchs/searchMembers.js"></script>
         <?php

        include './components/footer.php';
        ?>
    </body>
</html>