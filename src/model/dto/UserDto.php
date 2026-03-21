<?php

namespace model\dto;

readonly class UserDto {

    public function __construct(
        private string $id,
        private string $login,
        private string $phone,
        private string $email
    ) {}

    public function getId(): string {
        return $this->id;
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

    public function toArray(): array {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'phone' => $this->phone,
            'email' => $this->email
        ];
    }
}