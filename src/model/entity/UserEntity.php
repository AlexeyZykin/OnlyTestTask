<?php

namespace model\entity;

class UserEntity {

    private string $id;

    private string $login;

    private string $phone;

    private string $email;

    private string $password_hash;

    public function __construct(string $id, string $login, string $phone, string $email, string $passwordHash) {
        $this->id = $id;
        $this->login = $login;
        $this->phone = $phone;
        $this->email = $email;
        $this->password_hash = $passwordHash;
    }

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

    public function getPasswordHash(): string {
        return $this->password_hash;
    }

    public function setId(string $id): void {
        $this->id = $id;
    }

    public function setLogin(string $login): void {
        $this->login = $login;
    }

    public function setPhone(string $phone): void {
        $this->phone = $phone;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPasswordHash(string $password_hash): void {
        $this->password_hash = $password_hash;
    }

}