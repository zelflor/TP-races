<?php 
session_start();

$ip_bloquee = '172.16.1.10';
$ip_visiteur = $_SERVER['REMOTE_ADDR'];

if ($ip_visiteur === $ip_bloquee) {
    http_response_code(403);
    die('Accès refusé');
}


include_once './db/variables.php';


if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $nom = $_POST['nom'];
    $ville = $_POST['ville'];
    echo "<p>la course " . $nom . " dans la ville de  " . $ville . "</p>\n";

    if (isset($_POST['ville']) && isset($_POST['nom'])) {
        $nom = $_POST['nom'];
        $ville = $_POST['ville'];
        $date = $_POST['date'];
        $distance = $_POST['distance'];

    } 
    else {
        echo "<p>Toutes les données doivent être renseignées.</p>\n";
    }
    printf("nom = %s<BR>",$nom);
    printf("ville = %s\n",$ville);
    printf("date = %s\n",$date);
    printf("distance = %s\n",$distance);

    try {
        $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        echo "<p>Echec de connexion :" . $e->getMessage() ."</p>\n";
    }
    $conn = null;

    try {
        $conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO course (cou_nom, cou_ville, cou_date, cou_distance)
        VALUES (:nom, :ville, :date, :distance)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':ville', $ville);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':distance', $distance);
        $stmt->execute();
        echo "<p>Insertion(s) effectée(s) : " . $stmt->rowCount() . "</p>\n";
    } catch (PDOException $e) {
        echo "<p>Echec de l'insertion :" . $e->getMessage() ."</p>\n";
    }
    $conn = null;

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
        <link rel="stylesheet" href="css/pages/Forms.css">
        <link rel="shortcut icon" href="../media/favicon.png" type="image/x-icon">
</head>
    <body>
        <?php

        include './components/header.php';
        ?>


        <section>
            <!--  -->
                
            <form action="/ajouterCourse.php" method="post">

                <h2>Créé une nouvelle course</h2>
                <label for="nom">Nom:</label>
                <input name="nom" type="text" placeholder="la meilleur course au monde"><br>

                <label for="ville">Ville:</label>
                <input type="text" name="ville" placeholder="authon-la-plaine"><br>

                <label for="distance">Distance:</label>
                <input type="number" name="distance" placeholder="00"><br>

                <label for="date">Date :</label>
                <input type="date" id="date" name="date"><br>
                
                <button type="submit">Ajouter</button>
                <input type="reset" value="Effacer">

            </form>
        </section>
         <?php

        include './components/footer.php';
        ?>
    </body>
</html>