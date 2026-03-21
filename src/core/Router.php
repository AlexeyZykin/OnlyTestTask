<?php

namespace core;

use Exception;
use exception\NotFoundException;
use model\HttpMethod;
use model\Route;

class Router {

    private array $routes;

    public function __construct(private RequestFactory $requestFactory) {}

    public function register(HttpMethod $httpMethod, Route $path, $callback, $reqClass = ""): void {
        $this->routes[$httpMethod->value][$path->value] = [
            "callback" => $callback,
            "reqClass" => $reqClass
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

        $dtoClassName = $this->routes[$reqMethod][$path]["reqClass"];
        $callback = $this->routes[$reqMethod][$path]["callback"];

        if (empty($dtoClassName)) {
            call_user_func($callback);
            return;
        }

        $request = $this->requestFactory->create($dtoClassName);
        call_user_func($callback, $request);
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