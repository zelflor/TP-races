    
       <nav>
            <a href="index.php">
                Accueil
            </a>
            <?php 
            if (!empty($_SESSION['user'])){
                ?>
            <a href="logout.php">
                Se déconnecter
            </a>
                <?php
            }else {
                ?>
            <a href="register.php">
                S'inscrire
            </a>
            <a href="connexion.php">
                Se Connecter
            </a>
                <?php
            }
            
            ?>

            <a href="displayrace.php">
                Afficher Cources
            </a>
            <a href="displaymembers.php">
                Afficher Adhérents
            </a>
        </nav>