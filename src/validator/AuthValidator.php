<?php

namespace validator;

use exception\ValidationException;
use model\dto\LoginRequest;
use model\dto\RegisterRequest;
use util\PhoneFormatUtils;

class AuthValidator {

    /**
     * @throws ValidationException
     */
    public function validateRegisterRequest(RegisterRequest $request): void {
        $errors = [];

        $login = trim($request->getLogin());
        if (empty($login)) {
            $errors['login_error'] = "Отсутствует обязательное поле для ввода: Логин";
        }

        $phone = trim($request->getPhone());
        if (empty($phone)) {
            $errors['phone_error'] = "Отсутствует обязательное поле для ввода: Телефон";
        } elseif (!preg_match(PhoneFormatUtils::PHONE_REGEX, $phone)) {
            $errors['phone_error'] = "Телефон не соответсвует российскому формату";
        }

        $email = trim($request->getEmail());
        if (empty($email)) {
            $errors['email_error'] = "Отсутствует обязательное поле для ввода: Email";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email_error'] = "Неверный формат email";
        }

        $password = trim($request->getPassword());
        if (empty($password)) {
            $errors['password_error'] = "Отсутствует обязательное поле для ввода: Пароль";
        }

        $repeatPassword = trim($request->getRepeatPassword());
        if (empty($repeatPassword)) {
            $errors['repeat_password_error'] = "Отсутствует обязательное поле для ввода: Повторный пароль";
        }

        if ($password !== $repeatPassword) {
            $errors['incorrect_password'] = "Пароли не совпадают";
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

    }

    /**
     * @throws ValidationException
     */
    public function validateLoginRequest(LoginRequest $request): void {
        $errors = [];

        $phoneOrEmail = trim($request->getPhoneOrEmail());
        if (empty($phoneOrEmail)) {
            $errors['phone_or_email'] = "Отсутствует обязательное поле: телефон/почта";
        }

        $password = trim($request->getPassword());
        if (empty($password)) {
            $errors['password'] = "Введите пароль";
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }


}