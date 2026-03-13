<?php require '../private/components/navbar.php'; ?>

<?php
if(!isset($_SESSION["user"])){
    echo "Vous devez être connecté pour voir ce profil.";
    exit;
}

// Si on a un paramètre id dans l'URL, on l'utilise, sinon on prend l'utilisateur connecté
$profileId = isset($_GET["id"]) ? intval($_GET["id"]) : $_SESSION["user"];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$profileId]);
$user = $stmt->fetch();
if(!$user){ 
    echo "Utilisateur introuvable."; 
    exit; 
}
?>

<h1>Profil de <?= htmlspecialchars($user["username"]) ?></h1>

<div>
    <p><strong>ID :</strong> <?= $user["id"] ?></p>
    <p><strong>Admin :</strong> <?= $user["admin"] ? "Oui" : "Non" ?></p>
    <p><strong>Genre :</strong> <?= htmlspecialchars($user["genre"] ?? "Non renseigné") ?></p>
    <p><strong>Créé le :</strong> <?= $user["created_at"] ?></p>
</div>

<?php if($user["avatar"]): ?>
<img src="<?= htmlspecialchars($user["avatar"]) ?>" alt="avatar" width="100">
<?php endif; ?>

<?php if($user["banner"]): ?>
<img src="<?= htmlspecialchars($user["banner"]) ?>" alt="banner" width="300">
<?php endif; ?>

<?php if($profileId == $_SESSION["user"]): ?>
<button id="logoutBtn">Se déconnecter</button>
<pre id="logoutResult"></pre>

<script>
document.getElementById("logoutBtn").addEventListener("click", () => {
    fetch("/api?action=logout")
        .then(res => res.json())
        .then(data => {
            document.getElementById("logoutResult").textContent = JSON.stringify(data, null, 2);
            if(data.success) location.reload();
        });
});
</script>
<?php endif; ?>

<h2>Rewards de <?= htmlspecialchars($user["username"]) ?></h2>
<div id="rewardsList"></div>

<script>
fetch("/api?action=my_rewards&user_id=<?= $profileId ?>")
.then(res=>res.json())
.then(data=>{
    const container = document.getElementById("rewardsList");
    container.innerHTML = "";
    if(!data.success){
        container.textContent = data.error;
        return;
    }

    data.events.forEach(event=>{
        const div = document.createElement("div");
        div.style.border = "1px solid #ccc";
        div.style.margin = "5px";
        div.style.padding = "5px";
        div.style.display = "flex";
        div.style.alignItems = "center";

        const img = document.createElement("img");
        img.src = event.reward ? `/assets/${event.reward}` : "";
        img.width = 64;
        img.height = 64;
        img.style.marginRight = "10px";
        img.style.objectFit = "contain";
        div.appendChild(img);

        const info = document.createElement("div");
        info.innerHTML = `
            <strong>${event.name}</strong><br>
            Date: ${event.event_date}<br>
            Redeemed: ${event.redeemed ? "Oui" : "Non"}
        `;
        div.appendChild(info);

        container.appendChild(div);
    });
});
</script>