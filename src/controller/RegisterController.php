<?php

namespace controller;

use core\View;
use exception\AlreadyExistsException;
use exception\RegistrationFailedException;
use exception\ValidationException;
use model\dto\RegisterRequest;
use model\Route;
use model\Template;
use service\AuthService;
use service\SessionService;
use validator\AuthValidator;

readonly class RegisterController {

    public function __construct(
        private View $view,
        private AuthValidator $authValidator,
        private AuthService $authService,
        private SessionService $sessionService,
    ) {}

    public function show(): void {
        $this->view->render(Template::REGISTER->value);
    }

    public function register(RegisterRequest $request): void {
        try {
            $this->authValidator->validateRegisterRequest($request);

            $userDto = $this->authService->registerUser($request);

            $this->sessionService->set("user_id", $userDto->getId());
            $this->sessionService->regenerateId();

            header("Location:" . Route::PROFILE->value);
            exit;
        } catch (ValidationException | AlreadyExistsException | RegistrationFailedException $e) {
            $inputData = [
                "login" => $request->getLogin(),
                "phone" => $request->getPhone(),
                "email" => $request->getEmail(),
                "password" => $request->getPassword(),
                "repeat_password" => $request->getRepeatPassword()
            ];

            $this->sessionService->set("error", $e->getMessage());
            $this->sessionService->set("input_data", $inputData);

            header("Location:" . Route::REGISTER->value);
            exit;
        }
    }

}