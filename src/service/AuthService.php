<?php

namespace service;

use exception\AlreadyExistsException;
use exception\InvalidPasswordException;
use exception\RegistrationFailedException;
use exception\SmartCaptchaFailedException;
use exception\UserNotFoundException;
use exception\ValidationException;
use model\dto\LoginRequest;
use model\dto\RegisterRequest;
use model\dto\UserDto;
use model\entity\UserEntity;
use repository\UserRepository;
use util\PhoneFormatUtils;

class AuthService {

    public function __construct(
        private UserRepository $userRepository,
        private SmartCaptchaApi $smartCaptchaApi,
    ) {}

    /**
     * @throws AlreadyExistsException
     * @throws RegistrationFailedException
     * @throws ValidationException
     */
    public function registerUser(RegisterRequest $request): UserDto {
        $generatedPasswordHash =password_hash($request->getPassword(), PASSWORD_BCRYPT);
        $generatedId =uniqid();
        $normalizedPhone = PhoneFormatUtils::normalizePhone($request->getPhone());

        $entity = new UserEntity(
            id: $generatedId,
            login: $request->getLogin(),
            phone: $normalizedPhone,
            email: $request->getEmail(),
            passwordHash: $generatedPasswordHash
        );

        $existingUser = $this->userRepository->findUser(
            email: $entity->getEmail(),
            login: $entity->getLogin(),
            phone: $entity->getPhone()
        );

        if (isset($existingUser)) {
            $existingField = match (true) {
                $existingUser->getLogin() === $entity->getLogin() => "Логин",
                $existingUser->getEmail() === $entity->getEmail() => "Email",
                $existingUser->getPhone() === $entity->getPhone() => "Телефон",
                default => "",
            };

            throw new AlreadyExistsException("Пользователь с такими данными уже зарегистрирован: $existingField");
        }

        $savedUser = $this->userRepository->save($entity);

        if (!isset($savedUser)) {
            throw new RegistrationFailedException("Ошибка при регистрации. Попробуйте позже");
        }

        return $this->toDto($savedUser);
    }


    /**
     * @throws UserNotFoundException
     * @throws InvalidPasswordException
     * @throws ValidationException
     * @throws SmartCaptchaFailedException
     */
    public function authenticateUser(LoginRequest $request): UserDto {
        $this->smartCaptchaApi->checkCaptcha(token: $request->getSmartCaptchaToken(), ip: $_SERVER['REMOTE_ADDR']);

        $phoneOrEmail = preg_match(PhoneFormatUtils::PHONE_REGEX, $request->getPhoneOrEmail())
            ? PhoneFormatUtils::normalizePhone($request->getPhoneOrEmail())
            : $request->getPhoneOrEmail();

        $existingUser = $this->userRepository->findUserByPhoneOrEmail($phoneOrEmail);

        if (!isset($existingUser)) {
            throw new UserNotFoundException("Пользователь с такими данными не найден: {$request->getPhoneOrEmail()}");
        }

        $verify = password_verify($request->getPassword(), $existingUser->getPasswordHash());

        if (!$verify) {
            $msg = "Введен неверный пароль: {$request->getPassword()} от юзера {$request->getPhoneOrEmail()}";
            throw new InvalidPasswordException($msg);
        }

        return $this->toDto($existingUser);
    }

    private function toDto(UserEntity $entity): UserDto {
        return new UserDto(
            id: $entity->getId(),
            login: $entity->getLogin(),
            phone: $entity->getPhone(),
            email: $entity->getEmail()
        );
    }


}