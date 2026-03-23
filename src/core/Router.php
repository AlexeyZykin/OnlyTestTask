<?php

namespace core;

use Exception;
use exception\NotFoundException;
use model\HttpMethod;
use model\Route;

class Router {

    private array $routes;

    public function __construct() {}

    public function register(HttpMethod $httpMethod, Route $path, $callback, $request = null): void {
        $this->routes[$httpMethod->value][$path->value] = [
            "callback" => $callback,
            "request" => $request
        ];
    }

    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function handleRequest(string $reqMethod, string $uri): void {
        $path = parse_url($uri)["path"];

        $this->handleStaticFile($path);

        if (!isset($this->routes[$reqMethod]) || !isset($this->routes[$reqMethod][$path])) {
            throw new NotFoundException("Данный path не найден: $path");
        }

        $requestDto = $this->routes[$reqMethod][$path]["request"];
        $callback = $this->routes[$reqMethod][$path]["callback"];

        if (!isset($requestDto)) {
            call_user_func($callback);
            return;
        }

        call_user_func($callback, $requestDto);
    }


    private function handleStaticFile($path): void {
        $filePath = __DIR__ . "/../" . $path;
        if (!file_exists($filePath)) return;

        switch ($path) {
            case "/public/style.css": {
                header('Content-Type: text/css');
                readfile($filePath);
                exit;
            }
        }
    }
}