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
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
    <body>
      <?php

        include './components/header.php';
        ?>



        <section>
                        <div class="div-page-title-container">
                <h3>Liste des Courses</h3>
                <div class="div-input-search">
                
                    <input type="text" name="" id="input-text-search" placeholder="Rechercher une course">
                    <button id="btn-submit-search"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21.0002 21L16.7002 16.7M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg></button>
                </div>
            </div>
            <div class="div-race-flex-container">

              
                <a class="div-event-race" href="viewrace.php?id=1">
                    <img src="media/montagne.png" alt="Montagne">
                    <p>Chépaou</p>
                    <div class="div-race-info">
                        <h4>Course Montagne</h4>
                        
                        <h3>12/09/2025 à <span>9h30</span></h3>
                        <h5>50km</h5>
                    </div>
                </a>
                
                 <a class="div-event-race" href="viewrace.php?id=1">
                    <img src="media/montagne.png" alt="Montagne">
                    <p>Chépaou</p>
                    <div class="div-race-info">
                        <h4>Course Montagne</h4>
                        
                        <h3>12/09/2025 à <span>9h30</span></h3>
                        <h5>50km</h5>
                    </div>
                </a>
                <a class="div-event-race" href="viewrace.php?id=1">
                    <img src="media/montagne.png" alt="Montagne">
                    <p>Chépaou</p>
                    <div class="div-race-info">
                        <h4>Course Montagne</h4>
                        
                        <h3>12/09/2025 à <span>9h30</span></h3>
                        <h5>50km</h5>
                    </div>
                </a>
                


           </div>
        </section>
         <script src="./js/searchs/searchRaces.js"></script>

        <footer>
        <p>&copy; 2025 QUEIROZ Florian</p>
        </footer>
    </body>
</html>