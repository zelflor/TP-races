<?php
session_start();


$ip_bloquee = '172.16.1.10';
$ip_visiteur = $_SERVER['REMOTE_ADDR'];

if ($ip_visiteur === $ip_bloquee) {
    http_response_code(403);
    die('Accès refusé');
}

if (empty($_SESSION['user']) || $_SESSION['user']['admin'] != '1') {
    header('Location: /');
    exit();
}

include_once './db/variables.php';


$idrace = isset($_GET['course']) ? (int) $_GET['course'] : null;

if (!$idrace) {
    die("ID de course manquant dans l'URL (attendu : ?course=X).");
}

try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_licence'])) {
        $licence_cible = $_POST['user_licence'];

        if (!empty($licence_cible)) {

            $check = $conn->prepare("SELECT 1 FROM inscrire WHERE ins_adhLicence = :licence AND ins_couId = :course");
            $check->execute([':licence' => $licence_cible, ':course' => $idrace]);

            if (!$check->fetch()) {
                $stmt = $conn->prepare("INSERT INTO inscrire (ins_adhLicence, ins_couId, ins_date) VALUES (:licence, :course, CURDATE())");
                $stmt->execute([
                    ':licence' => $licence_cible,
                    ':course'  => $idrace
                ]);
            }

            header("Location: viewrace.php?id=" . $idrace);
            exit();
        }
    }

} catch (PDOException $e) {
    die("Erreur base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un adhérent</title>
    <link rel="stylesheet" href="css/style.css">
    
        <link rel="stylesheet" href="css/pages/Connexion.css">
        <link rel="stylesheet" href="css/pages/Forms.css">
</head>
<body>
    <?php include './components/header.php'; ?>

    <section>
        <form action="ajouterAdherent.php?course=<?php echo $idrace; ?>" method="post">
            <h2>Inscrire un membre à la course n°<?php echo $idrace; ?></h2>
            
            <label for="user">Choisir l'adhérent :</label>
            <select name="user_licence" id="user" required>
                <option value="">-- Sélectionner --</option>
                <?php 

                $stmtUsers = $conn->query("SELECT adh_licence, adh_nom, adh_prenom FROM adherent ORDER BY adh_nom ASC");
                while ($u = $stmtUsers->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$u['adh_licence']}'>{$u['adh_nom']} {$u['adh_prenom']}</option>";
                }
                ?>
            </select>
            
            <button type="submit">Confirmer l'ajout</button>
            <p><a href="viewrace.php?id=<?php echo $idrace; ?>">Retour sans ajouter</a></p>
        </form>
    </section>

    <?php include './components/footer.php'; ?>
</body>
</html>