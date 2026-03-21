<?php

namespace controller;

use core\View;
use exception\AlreadyExistsException;
use exception\ProfileAccessDeniedException;
use exception\UpdateFailedException;
use exception\UserNotFoundException;
use exception\ValidationException;
use model\dto\UpdateUserRequest;
use model\LogLevel;
use model\Route;
use model\Template;
use service\SessionService;
use service\UserService;
use validator\UserValidator;
use function util\log;

readonly class ProfileController {

    public function __construct(
        private View $view,
        private SessionService $sessionService,
        private UserService $userService,
        private UserValidator $userValidator,
    ) {}

    public function show(): void {
        try {
            $this->checkAccess();

            $userId = $this->sessionService->get("user_id");

            $userDto = $this->userService->findById($userId);

            $this->view->render(Template::PROFILE->value, $userDto->toArray());
        } catch (UserNotFoundException | ProfileAccessDeniedException $e) {
            log($e->getMessage(), LogLevel::INFO);
            header("Location:" . Route::HOME->value);
            exit;
        }
    }


    public function updateUser(UpdateUserRequest $request): void {
        try {
            $this->checkAccess();

            $this->userValidator->validateUpdateRequest($request);

            $userId = $this->sessionService->get("user_id");

            $updatedUser = $this->userService->updateUser($request, $userId);

            header("Location:" . Route::PROFILE->value);
            exit;
        } catch (ProfileAccessDeniedException | UserNotFoundException $e) {
            header("Location:" . Route::HOME->value);
            exit;
        } catch (AlreadyExistsException | UpdateFailedException | ValidationException $e) {
            $this->sessionService->set("error", $e->getMessage());

            $this->sessionService->set("input_data", $request->toArray());

            header("Location:" . Route::PROFILE->value);
            exit;
        }
    }

    /**
     * @throws ProfileAccessDeniedException
     */
    private function checkAccess(): void {
        $isUnauthorized  = !$this->sessionService->has("user_id");

        if ($isUnauthorized) {
            throw new ProfileAccessDeniedException("Нет доступа к профилю.");
        }
    }

}