<?php

require __DIR__ . "/../private/db.php";

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$uri = rtrim($uri, "/");
if ($uri === "") {
    $uri = "/";
}

if (str_starts_with($uri, "/api")) {
    require __DIR__ . "/../private/api.php";
    exit;
}

switch ($uri) {

    case "/":
        require __DIR__ . "/../private/templates/home.php";
        break;

    case "/profile":
        require __DIR__ . "/../private/templates/profile.php";
        break;
    case "/auth":
        require __DIR__ . "/../private/templates/auth.php";
        break;
    case "/events":
        require __DIR__ . "/../private/templates/events.php";
        break;
    case "/create_event":
        require __DIR__ . "/../private/templates/create_event.php";
        break;
    default:
        http_response_code(404);
        require __DIR__ . "/../private/templates/404.php";
        break;
}