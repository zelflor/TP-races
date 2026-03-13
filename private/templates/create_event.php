<?php require '../private/components/navbar.php'; ?>
<?php
if(!isset($_SESSION["user"])){
    echo "Vous devez être connecté pour créer un event.";
    exit;
}

$stmt = $pdo->prepare("SELECT admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user"]]);
$user = $stmt->fetch();
if(!$user["admin"]){
    echo "Seuls les admins peuvent créer des events.";
    exit;
}
?>

<h1>Créer un Event</h1>

<form id="eventForm">
<input type="text" name="name" placeholder="Nom de l'event" required>
<textarea name="description" placeholder="Description de l'event"></textarea>
<input type="datetime-local" name="event_date" required>
<input type="text" name="banner" placeholder="URL Banner (optionnel)">
<input type="text" name="reward" placeholder="Reward (optionnel)">
<button type="submit">Créer</button>
</form>

<pre id="result"></pre>

<script>
document.getElementById("eventForm").addEventListener("submit", e => {
    e.preventDefault();
    const data = new FormData(e.target);
    fetch("/api?action=create_event", {method:"POST", body:data})
        .then(res => res.json())
        .then(d=>{
            document.getElementById("result").textContent = JSON.stringify(d, null,2);
            if(d.success) e.target.reset();
        });
});
</script>


