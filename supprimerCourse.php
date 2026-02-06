<?php 
session_start();
include_once './db/variables.php';

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

$idrace = isset($_GET['course']) ? (int) $_GET['course'] : null;

if (!$idrace) {
    die("Erreur : L'identifiant de la course est manquant dans l'URL (attendu : ?course=X)");
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_id'])) {
        $id_to_delete = (int) $_POST['confirm_id'];
        
        $delInsc = $conn->prepare("DELETE FROM inscrire WHERE ins_couId = :id");
        $delInsc->execute([':id' => $id_to_delete]);

        $stmt = $conn->prepare("DELETE FROM course WHERE cou_id = :id");
        $stmt->execute([':id' => $id_to_delete]);

        header('Location: index.php?msg=deleted');
        exit();
    }

    $stmt = $conn->prepare("SELECT cou_id, cou_nom FROM course WHERE cou_id = :id");
    $stmt->execute([':id' => $idrace]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        die("Erreur : La course n $idrace n'existe pas dans la base de données.");
    }

} catch (PDOException $e) {
    die("Erreur base de données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer la course</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pages/Forms.css">
</head>
<body>
    <?php include './components/header.php'; ?>

    <section>
        <form action="supprimerCourse.php?course=<?php echo $idrace; ?>" method="post">
            <h2>Voulez-vous vraiment supprimer la course : <br>
                "<?php echo htmlspecialchars($result['cou_nom']); ?>" ?
            </h2>
            
            <input type="hidden" name="confirm_id" value="<?php echo $result['cou_id']; ?>">
            
            <div>
                <button type="submit" >
                    Confirmer la suppression
                </button>
                <a href="viewrace.php?id=<?php echo $idrace; ?>" style="margin-left: 10px;">Annuler</a>
            </div>
        </form>
    </section>

    <?php include './components/footer.php'; ?>
</body>
</html>