<?php

use config\Config;
use controller\HomeController;
use controller\LoginController;
use controller\ProfileController;
use controller\RegisterController;
use core\DbConnectionProvider;
use core\DbInitializer;
use core\RequestFactory;
use core\Router;
use core\View;
use handler\GlobalExceptionHandler;
use model\dto\LoginRequest;
use model\dto\RegisterRequest;
use model\dto\UpdateUserRequest;
use model\HttpMethod;
use model\Route;
use repository\UserRepository;
use service\AuthService;
use service\SessionService;
use service\SmartCaptchaApi;
use service\UserService;
use validator\AuthValidator;
use validator\UserValidator;

require_once __DIR__ . "/../classLoader.php";
require_once __DIR__ . "/../util/log.php";

// todo создание зависимостей делегировать какому-нибудь DI-контейнеру

$globalExceptionHandler = new GlobalExceptionHandler();

set_exception_handler([$globalExceptionHandler, 'handleException']);
set_error_handler([$globalExceptionHandler, 'handleError']);

$config = Config::getInstance();
$dbConn = DbConnectionProvider::getConnection(
    dsn: $config->DB_DSN(),
    username: $config->DB_USERNAME(),
    password: $config->DB_PASSWORD()
);
DbInitializer::init($dbConn);

$requestFactory = new RequestFactory();

$router = new Router($requestFactory);

$view = new View();

$authValidator = new AuthValidator();
$userValidator = new UserValidator();

$userRepository = new UserRepository($dbConn);

$smartCaptchaApi = new SmartCaptchaApi($config);
$authService = new AuthService($userRepository, $smartCaptchaApi);
$sessionService = new SessionService();
$userService = new UserService($userRepository);

$homeController = new HomeController($view);
$registerController = new RegisterController($view, $authValidator, $authService, $sessionService);
$loginController = new LoginController($view, $authValidator, $authService, $sessionService, $config);
$profileController = new ProfileController($view, $sessionService, $userService, $userValidator);

$router->register(HttpMethod::GET, Route::HOME, [$homeController, 'show']);
$router->register(HttpMethod::GET, Route::REGISTER, [$registerController, 'show']);
$router->register(HttpMethod::GET, Route::LOGIN, [$loginController, 'show']);
$router->register(HttpMethod::GET, Route::PROFILE, [$profileController, 'show']);

$router->register(
    httpMethod: HttpMethod::POST,
    path: Route::REGISTER,
    callback: [$registerController, 'register'],
    requestClass: RegisterRequest::class
);
$router->register(
    httpMethod: HttpMethod::POST,
    path: Route::LOGIN,
    callback: [$loginController, 'login'],
    requestClass: LoginRequest::class
);
$router->register(
    httpMethod: HttpMethod::POST,
    path: Route::PROFILE,
    callback: [$profileController, 'updateUser'],
    requestClass: UpdateUserRequest::class
);

$router->handleRequest(reqMethod: $_SERVER["REQUEST_METHOD"], uri: $_SERVER["REQUEST_URI"]);