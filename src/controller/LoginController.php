<?php

namespace controller;

use config\Config;
use core\View;
use exception\InvalidPasswordException;
use exception\SmartCaptchaFailedException;
use exception\UserNotFoundException;
use exception\ValidationException;
use model\dto\LoginRequest;
use model\Route;
use model\Template;
use service\AuthService;
use service\SessionService;
use validator\AuthValidator;

readonly class LoginController {

    public function __construct(
        private View $view,
        private AuthValidator $authValidator,
        private AuthService $authService,
        private SessionService $sessionService,
        private Config $config,
    ) {}

    public function show(): void {
        $this->sessionService->set("smart_captcha_client", $this->config->SMART_CAPTCHA_CLIENT_KEY());

        $this->view->render(Template::LOGIN->value);
    }

    public function login(LoginRequest $request): void {
        try {
            $this->authValidator->validateLoginRequest($request);

            $userDto = $this->authService->authenticateUser($request);

            $this->sessionService->set("user_id", $userDto->getId());
            $this->sessionService->regenerateId();

            header("Location:" . Route::PROFILE->value);
            exit;
        } catch (
            ValidationException | UserNotFoundException |
            InvalidPasswordException | SmartCaptchaFailedException $e
        ) {
            $inputData = [
                "phone_or_email" => $request->getPhoneOrEmail(),
                "password" => $request->getPassword()
            ];

            $this->sessionService->set("error", $e->getMessage());
            $this->sessionService->set("input_data", $inputData);

            header("Location:" . Route::LOGIN->value);
            exit;
        }

    }

}