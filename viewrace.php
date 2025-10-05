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



        <section>
                <div class="banner-container">
                    <img src="media/montagne.png" alt="">
                    <h1>Montagne</h1>
                </div>
                <div class="races-info-container">
                    <h2 class="races-name">Course Montagne</h2>
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

        include './components/footer.php';
        ?>
    </body>
</html>