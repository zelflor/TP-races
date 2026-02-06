<?php 
session_start();

include_once './db/variables.php';

// 1. Sécurité IP
$ip_bloquee = '172.16.1.10';
$ip_visiteur = $_SERVER['REMOTE_ADDR'];

if ($ip_visiteur === $ip_bloquee) {
    http_response_code(403);
    die('Accès refusé');
}

// 2. Récupération de l'ID via l'URL (?id=...)
$id_user = isset($_GET['id']) ? $_GET['id'] : null;
$user = null;

if ($id_user) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $stmt = $conn->prepare("SELECT * FROM adherent WHERE adh_licence = :id");
        $stmt->execute([':id' => $id_user]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $error = "Erreur de connexion : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?php echo $user ? htmlspecialchars($user['adh_nom']) : 'Utilisateur'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pages/profile.css">
</head>
<body>
    <?php include './components/header.php'; ?>

    <section>
        <?php if ($user): ?>

            <div class="div-profile-container-header">
            <img class="profile-picture" src="/uploads/profile_picture/<?php echo htmlspecialchars($user['adh_avatar']); ?>" 
                                alt="Avatar">
                                
                                
                <?php if ($user['adh_banner']): ?>           
                <img src="/uploads/profile_banner/<?php echo htmlspecialchars($user['adh_banner']); ?>" class="banner-profile">
                <?php endif?>

                <h2><?php echo htmlspecialchars($user['adh_nom']); ?> <?php echo htmlspecialchars($user['adh_prenom']); ?></h2>
            </div>
            
                <p><strong>Sexe :</strong> <?php echo $user['adh_sexe'] == 'M' ? 'Homme' : 'Femme'; ?></p>
   
        <?php else: ?>
            <p>Utilisateur introuvable ou ID manquant.</p>
        <?php endif; ?>
        
        <br>
        <a href="index.php">Retour à la liste</a>
    </section>

    <?php include './components/footer.php'; ?>
</body>
</html>