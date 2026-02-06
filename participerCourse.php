<?php
session_start();

if (empty($_SESSION['user'])) {
    header('Location: /');
    exit();
}
$ip_bloquee = '172.16.1.10';
$ip_visiteur = $_SERVER['REMOTE_ADDR'];

if ($ip_visiteur === $ip_bloquee) {
    http_response_code(403);
    die('Accès refusé');
}


include_once './db/variables.php';

$idrace = isset($_GET['course']) ? (int) $_GET['course'] : null;
$message = "";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>Participer à une course</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pages/Connexion.css">
    <link rel="stylesheet" href="css/pages/Forms.css">
</head>

<body>

<?php include './components/header.php'; ?>

<section>

<?php
try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!$idrace) {
            $message = "Identifiant de course manquant.";
        } else {

            $stmt = $conn->prepare(
                "SELECT cou_id, cou_nom FROM course WHERE cou_id = :idrace"
            );
            $stmt->execute([':idrace' => $idrace]);
            $course = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$course) {
                $message = "Cette course n'existe pas.";
            } else {
                // Vérifier si déjà inscrit
                $stmt = $conn->prepare(
                    "SELECT 1 FROM inscrire 
                     WHERE ins_adhLicence = :licence 
                     AND ins_couId = :course"
                );
                $stmt->execute([
                    ':licence' => $_SESSION['user']['adh_licence'],
                    ':course'  => $idrace
                ]);

                if ($stmt->fetch()) {

                    $stmt = $conn->prepare(
                        "DELETE FROM inscrire
                        WHERE ins_adhLicence = :licence
                        AND ins_couId = :course"
                    );
                    $stmt->execute([
                        ':licence' => $_SESSION['user']['adh_licence'],
                        ':course'  => $idrace
                    ]);

                    $message = "Tu es désinscrit(e) de la course.";

                    
                } else {

                    // Inscription
                    $stmt = $conn->prepare(
                        "INSERT INTO inscrire 
                        (ins_adhLicence, ins_couId, ins_date)
                        VALUES (:licence, :course, CURDATE())"
                    );
                    $stmt->execute([
                        ':licence' => $_SESSION['user']['adh_licence'],
                        ':course'  => $idrace
                    ]);

                    $message = "Inscription validée";
                }
            }
        }

        echo "<p>$message</p>";
    }

    else {

        if (!$idrace) {
            echo "<p>Course introuvable.</p>";
        } else {

            $stmt = $conn->prepare(
                "SELECT cou_id, cou_nom FROM course WHERE cou_id = :idrace"
            );
            $stmt->execute([':idrace' => $idrace]);
            $course = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$course) {
                echo "<p>Cette course n'existe pas.</p>";
            } else {
                ?>
                <form action="participerCourse.php?course=<?php echo $idrace; ?>" method="post">

                    <img class="img-avatar"
                         src="/uploads/profile_picture/<?php echo htmlspecialchars($_SESSION['user']['adh_avatar']); ?>"
                         alt="avatar">

                    <hr>

                    <h2>
                        Participer à la course :
                        "<?php echo htmlspecialchars($course['cou_nom']); ?>"
                        (<?php echo $course['cou_id']; ?>)
                    </h2>

                    <button type="submit">Oui, je participe</button>
                </form>
                <?php
            }
        }
    }

    $conn = null;

} catch (PDOException $e) {
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

</section>

<?php include './components/footer.php'; ?>

</body>
</html>