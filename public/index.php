<?php

require "router.php";

$router = new Router("../private/templates/");

$router->get("/", "home.php");
$router->get("/profile", "profile.php");

$router->run();