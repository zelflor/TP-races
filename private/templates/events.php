<?php require '../private/components/navbar.php'; ?>
<?php
require '../private/components/render_items.php';

$userId = $_SESSION["user"] ?? null;
$isAdmin = false;

if($userId){
    $stmt = $pdo->prepare("SELECT id, username, admin FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    $isAdmin = $user["admin"];
}
?>

<h1>Events</h1>

<?php if($isAdmin): ?>
<p>Vous êtes admin, vous pouvez créer/supprimer/attribuer winner.</p>
<a href="/create_event">Créer un nouvel event</a>
<?php endif; ?>

<div id="eventsList">
<?php
$stmt = $pdo->query("SELECT e.*, u.username as created_by_name FROM events e LEFT JOIN users u ON e.created_by = u.id ORDER BY e.event_date DESC");
$events = $stmt->fetchAll();

foreach($events as $event):
?>
<style>
    .event {
        border:1px solid #ccc; margin:10px; padding:10px;
    }
    .event canvas {
        width: 300px!important;
        height: 300px!important;
        aspect-ratio: 1 / 1;
    }
</style>
<div class="event" >
    <h3><?= htmlspecialchars($event['name']) ?></h3>
    <p>Description: <?= htmlspecialchars($event['description'] ?? '-') ?></p>
    <p>Date: <?= htmlspecialchars($event['event_date']) ?></p>
    <p>Créé par: <?= htmlspecialchars($event['created_by_name'] ?? '-') ?></p>
   <p>Winner: 
<?php
if(!empty($event['winner'])){
    $stmtU = $pdo->prepare("SELECT id, username, avatar FROM users WHERE id = ?");
    $stmtU->execute([$event['winner']]);
    $winnerUser = $stmtU->fetch();
    if($winnerUser){
        $avatar = !empty($winnerUser['avatar']) ? '<img src="'.htmlspecialchars($winnerUser['avatar']).'" alt="avatar" width="30" style="vertical-align:middle; margin-right:5px;">' : '';
        echo '<a href="/profile?id='.$winnerUser['id'].'">'.$avatar.htmlspecialchars($winnerUser['username']).'</a>';
    } else {
        echo 'Inconnu';
    }
} else {
    echo '-';
}
?>
</p>
    <p>Reward:</p>
    <?php if(!empty($event['reward'])): ?>
        <?php renderThickImage(
            imagePath: '/assets/' . $event['reward'],
            layerCount: 30
        ); ?>
    <?php else: ?>
        -
    <?php endif; ?>
    <p>Redeemed: <?= $event['redeemed'] ? 'Oui' : 'Non' ?></p>

    <?php if($isAdmin): ?>
        <select>
        <?php
        $usersStmt = $pdo->query("SELECT id, username FROM users");
        foreach($usersStmt->fetchAll() as $u):
        ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
        <?php endforeach; ?>
        </select>
        <button onclick="setWinner(<?= $event['id'] ?>, this.previousElementSibling.value)">Set Winner</button>
        <button onclick="removeEvent(<?= $event['id'] ?>)">Supprimer Event</button>
    <?php elseif($userId == $event['winner'] && !$event['redeemed']): ?>
        <button onclick="redeemReward(<?= $event['id'] ?>)">Redeem Reward</button>
    <?php endif; ?>
</div>
<?php endforeach; ?>
</div>

<script>
function setWinner(eventId, userId){
    fetch("/api?action=set_winner", {
        method:"POST",
        body: new URLSearchParams({event_id:eventId, winner_id:userId})
    }).then(r=>r.json()).then(r=>location.reload());
}

function removeEvent(eventId){
    if(!confirm("Supprimer cet event ?")) return;
    fetch("/api?action=remove_event", {
        method:"POST",
        body: new URLSearchParams({event_id:eventId})
    }).then(r=>r.json()).then(r=>location.reload());
}

function redeemReward(eventId){
    fetch("/api?action=redeem_reward", {
        method:"POST",
        body: new URLSearchParams({event_id:eventId})
    }).then(r=>r.json()).then(r=>{
        alert(r.success ? "Reward redeemed !" : r.error);
        location.reload();
    });
}
</script>