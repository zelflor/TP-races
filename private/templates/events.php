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
    .participants img {
        width: 30px; height: 30px; border-radius: 50%; margin-right: 5px; vertical-align: middle;
    }
</style>
<div class="event" data-event-id="<?= $event['id'] ?>">
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
            $avatar = !empty($winnerUser['avatar']) ? '<img src="'.htmlspecialchars($winnerUser['avatar']).'" alt="avatar">' : '';
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

    <div class="participants">
        <strong>Participants:</strong>
        <?php
        $stmtP = $pdo->prepare("SELECT u.id, u.username, u.avatar FROM participants p JOIN users u ON p.user_id = u.id WHERE p.event_id = ?");
        $stmtP->execute([$event['id']]);
        $participants = $stmtP->fetchAll();
        foreach($participants as $p){
            $avatar = !empty($p['avatar']) ? '<img src="'.htmlspecialchars($p['avatar']).'" alt="avatar">' : '';
            echo '<a href="/profile?id='.$p['id'].'">'.$avatar.htmlspecialchars($p['username']).'</a> ';
        }
        ?>
    </div>

    <?php if(!$event['winner'] && $userId): ?>
        <?php
        $isParticipating = false;
        foreach($participants as $p){
            if($p['id'] == $userId){ $isParticipating = true; break; }
        }
        ?>
        <?php if($isParticipating): ?>
            <button onclick="removeParticipation(<?= $event['id'] ?>)">Se retirer</button>
        <?php else: ?>
            <button onclick="participate(<?= $event['id'] ?>)">Participer</button>
        <?php endif; ?>
    <?php endif; ?>

    <?php if($isAdmin && $participants): ?>
        <select>
        <?php foreach($participants as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['username']) ?></option>
        <?php endforeach; ?>
        </select>
        <button onclick="setWinner(<?= $event['id'] ?>, this.previousElementSibling.value)">Set Winner</button>
    <?php endif; ?>

    <?php if($isAdmin): ?>
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

function participate(eventId){
    fetch("/api?action=participate", {
        method:"POST",
        body: new URLSearchParams({event_id:eventId})
    }).then(r=>r.json()).then(r=>{
        if(!r.success) alert(r.error);
        location.reload();
    });
}

function removeParticipation(eventId){
    fetch("/api?action=remove_participation", {
        method:"POST",
        body: new URLSearchParams({event_id:eventId})
    }).then(r=>r.json()).then(r=>{
        if(!r.success) alert(r.error);
        location.reload();
    });
}
</script>