

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
        <link rel="stylesheet" href="css/pages/displayRace.css">
        <link rel="shortcut icon" href="../media/favicon.png" type="image/x-icon">
</head>
    <body>
      <?php

        include './components/header.php';
        ?>



        <section>
   
        
            <div class="div-race-flex-container">

                <div class="div-races-list">
                   <?php 
                
            
                try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("SELECT * FROM course ORDER BY cou_date DESC LIMIT 100");
                $stmt->execute();
                $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($resultats) {
                    foreach ($resultats as $result){
                        ?>
                       
                         <a class="div-event-race" href="viewrace.php?id=<?php echo $result['cou_id']; ?>">
                            <img src="media/montagne.png" alt="Montagne">
                            <p><?php echo $result['cou_ville']; ?></p>
                            <div class="div-race-info">
                                <h4> <?php echo $result['cou_nom']; ?></h4>

                                <h3><?php echo $result['cou_date']; ?> à <span>9h30</span></h3>
                                <h5> <?php echo $result['cou_distance']; ?>km</h5>
                            </div>
                        </a>
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
                
                   
                </div>
                <div class="div-filter-list">
                    <div class="div-input-search">
                                    
                                        <input type="text" name="" id="input-text-search" placeholder="Rechercher une course">
                                        <button id="btn-submit-search"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M21.0002 21L16.7002 16.7M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg></button>
                                    </div>
                            <?php
                            if (!empty($_SESSION['user'])){
                                if ($_SESSION['user']['admin'] == '1'){

                                
                                ?>
                                    <a href="/ajouterCourse.php">
                                        <button class="button-add-race">Ajouter une course</button>
                                    </a>
                                <?php
                                }
                            }
                            ?>

                </div>

           </div>
           
        </section>
         <script src="./js/searchs/searchRaces.js"></script>

         <?php

        include './components/footer.php';
        ?>
    </body>
</html>