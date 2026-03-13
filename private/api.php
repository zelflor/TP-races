<?php

header("Content-Type: application/json");

$action = $_POST["action"] ?? $_GET["action"] ?? null;

switch ($action) {

    case "register":

        $username = $_POST["username"] ?? "";
        $password = $_POST["password"] ?? "";

        if (!$username || !$password) {
            echo json_encode(["error" => "missing fields"]);
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            echo json_encode(["error" => "username exists"]);
            exit;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            INSERT INTO users (username, password)
            VALUES (?, ?)
        ");

        $stmt->execute([$username, $hash]);

        echo json_encode([
            "success" => true
        ]);

        break;


    case "login":

        $username = $_POST["username"] ?? "";
        $password = $_POST["password"] ?? "";

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user["password"])) {

            echo json_encode([
                "error" => "invalid credentials"
            ]);

            exit;
        }
        $_SESSION["user"] = $user["id"];
        echo json_encode([
            "success" => true,
            "user" => [
                "id" => $user["id"],
                "username" => $user["username"],
                "avatar" => $user["avatar"],
                "banner" => $user["banner"],
                "admin" => $user["admin"],
                "genre" => $user["genre"]
            ]
        ]);

        break;

    case "logout":

        session_destroy();

        echo json_encode([
            "success" => true
        ]);

        break;
    case "me":

        if(!isset($_SESSION["user"])){
            echo json_encode(["error" => "not logged in"]);
            exit;
        }

        $stmt = $pdo->prepare("SELECT id, username, avatar, banner, admin, genre, created_at FROM users WHERE id = ?");
        $stmt->execute([$_SESSION["user"]]);

        $user = $stmt->fetch();

        if(!$user){
            echo json_encode(["error" => "user not found"]);
            exit;
        }

        echo json_encode([
            "success" => true,
            "user" => $user
        ]);

        break;
    case "create_event":

        if(!isset($_SESSION["user"])){
            echo json_encode(["error" => "not logged in"]);
            exit;
        }

        $stmt = $pdo->prepare("SELECT admin FROM users WHERE id = ?");
        $stmt->execute([$_SESSION["user"]]);
        $user = $stmt->fetch();

        if(!$user["admin"]){
            echo json_encode(["error" => "only admins can create events"]);
            exit;
        }

        $name = $_POST["name"] ?? "";
        $description = $_POST["description"] ?? "";
        $banner = $_POST["banner"] ?? null;
        $reward = $_POST["reward"] ?? null;
        $event_date = $_POST["event_date"] ?? null;

        if(!$name || !$event_date){
            echo json_encode(["error" => "name and event_date are required"]);
            exit;
        }

        $stmt = $pdo->prepare("
            INSERT INTO events (name, description, created_by, banner, reward, event_date)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([$name, $description, $_SESSION["user"], $banner, $reward, $event_date]);

        echo json_encode([
            "success" => true,
            "event_id" => $pdo->lastInsertId()
        ]);

        break;

    case "remove_event":

        if(!isset($_SESSION["user"])){
            echo json_encode(["error" => "not logged in"]);
            exit;
        }

        $stmt = $pdo->prepare("SELECT admin FROM users WHERE id = ?");
        $stmt->execute([$_SESSION["user"]]);
        $user = $stmt->fetch();

        if(!$user["admin"]){
            echo json_encode(["error" => "only admins can remove events"]);
            exit;
        }

        $event_id = $_POST["event_id"] ?? null;
        if(!$event_id){
            echo json_encode(["error" => "event_id required"]);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$event_id]);

        echo json_encode(["success" => true]);

        break;

    case "set_winner":

        if(!isset($_SESSION["user"])){
            echo json_encode(["error" => "not logged in"]);
            exit;
        }

        $stmt = $pdo->prepare("SELECT admin FROM users WHERE id = ?");
        $stmt->execute([$_SESSION["user"]]);
        $user = $stmt->fetch();

        if(!$user["admin"]){
            echo json_encode(["error" => "only admins can set winner"]);
            exit;
        }

        $event_id = $_POST["event_id"] ?? null;
        $winner_id = $_POST["winner_id"] ?? null;

        if(!$event_id || !$winner_id){
            echo json_encode(["error" => "event_id and winner_id required"]);
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE events
            SET winner = ?, redeemed = 1
            WHERE id = ?
        ");
        $stmt->execute([$winner_id, $event_id]);

        echo json_encode(["success" => true]);

        break;

  case "redeem_reward":

    if(!isset($_SESSION["user"])){
        echo json_encode(["error" => "not logged in"]);
        exit;
    }

    $event_id = $_POST["event_id"] ?? null;
    if(!$event_id){
        echo json_encode(["error" => "event_id required"]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT winner, redeemed FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch();

    if(!$event){
        echo json_encode(["error" => "event not found"]);
        exit;
    }

    if($event["winner"] != $_SESSION["user"]){
        echo json_encode(["error" => "you are not the winner"]);
        exit;
    }

    if($event["redeemed"]){
        echo json_encode(["error" => "reward already redeemed"]);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE events SET redeemed = 1 WHERE id = ?");
    $stmt->execute([$event_id]);

    echo json_encode([
        "success" => true,
        "message" => "Reward redeemed successfully"
    ]);

    break;
    
    case "list_events":
        $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC");
        $events = $stmt->fetchAll();
        echo json_encode(["success"=>true, "events"=>$events]);
        break;
    case "list_users":

        if(!isset($_SESSION["user"])){
            echo json_encode(["error" => "not logged in"]);
            exit;
        }

        $stmt = $pdo->prepare("SELECT admin FROM users WHERE id = ?");
        $stmt->execute([$_SESSION["user"]]);
        $user = $stmt->fetch();

        if(!$user["admin"]){
            echo json_encode(["error" => "only admins can list users"]);
            exit;
        }

        $stmt = $pdo->query("SELECT id, username FROM users ORDER BY username ASC");
        $users = $stmt->fetchAll();

        echo json_encode([
            "success" => true,
            "users" => $users
        ]);

        break;
    case "my_rewards":

        if(!isset($_SESSION["user"])){
            echo json_encode(["error"=>"not logged in"]);
            exit;
        }

        $user_id = isset($_GET["user_id"]) ? intval($_GET["user_id"]) : $_SESSION["user"];

        $stmt = $pdo->prepare("
            SELECT e.id, e.name, e.reward, e.banner, e.redeemed, e.event_date
            FROM events e
            WHERE e.winner = ?
            ORDER BY e.event_date DESC
        ");
        $stmt->execute([$user_id]);

        $events = $stmt->fetchAll();

        echo json_encode([
            "success" => true,
            "events" => $events
        ]);

    break;
    default:

        echo json_encode([
            "error" => "unknown action"
        ]);

}