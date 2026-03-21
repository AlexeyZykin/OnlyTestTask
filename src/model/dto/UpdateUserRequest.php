<?php

namespace model\dto;

readonly class UpdateUserRequest {

    private string $login;

    private string $phone;

    private string $email;

    private string $password;

    public function __construct(array $data) {
        $this->login = trim($data["login"] ?? '');
        $this->phone = trim($data["phone"] ?? '');
        $this->email = trim(strtolower($data["email"] ?? ''));
        $this->password = trim($data["password"] ?? '');
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

    public function toArray(): array {
        return [
            "login" => $this->login,
            "phone" => $this->phone,
            "email" => $this->email,
            "password" => $this->password
        ];
    }

}