<?php

class Router {

    private $routes = [];
    private $basePath;

    public function __construct($basePath) {
        $this->basePath = rtrim($basePath, "/") . "/";
    }

    public function get($path, $file) {
        $this->routes["GET"][$path] = $file;
    }

    public function run() {

        $method = $_SERVER["REQUEST_METHOD"];
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        $uri = rtrim($uri, "/");
        if ($uri === "") $uri = "/";

        if (isset($this->routes[$method][$uri])) {

            $file = $this->basePath . $this->routes[$method][$uri];

            if (file_exists($file)) {
                require $file;
                return;
            }
        }

        http_response_code(404);
        require $this->basePath . "404.php";
    }
}