<?php

namespace model\dto;

readonly class RegisterRequest {

    private string $login;

    private string $phone;

    private string $email;

    private string $password;

    private string $repeatPassword;

    public function __construct(array $data) {
        $this->login = trim($data['login'] ?? '');
        $this->phone = trim($data['phone'] ?? '');
        $this->email = trim(strtolower($data['email'] ?? ''));
        $this->password = trim($data['password'] ?? '');
        $this->repeatPassword = trim($data['repeat_password'] ?? '');
    }

    public function getLogin(): string {
        return $this->login;
    }

    public function getPhone(): string {
        return $this->phone;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRepeatPassword(): string {
        return $this->repeatPassword;
    }
}