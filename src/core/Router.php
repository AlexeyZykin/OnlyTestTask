<?php

namespace core;

use Exception;
use exception\NotFoundException;
use model\HttpMethod;
use model\Route;

class Router {

    private array $routes;

    public function __construct(private readonly RequestFactory $requestFactory) {}

    public function register(HttpMethod $httpMethod, Route $path, $callback, $requestClass = ""): void {
        $this->routes[$httpMethod->value][$path->value] = [
            "callback" => $callback,
            "requestClass" => $requestClass
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

        $requestClass = $this->routes[$reqMethod][$path]["requestClass"];
        $callback = $this->routes[$reqMethod][$path]["callback"];

        if (empty($requestClass)) {
            call_user_func($callback);
            return;
        }

        $request = $this->requestFactory->create($requestClass);
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