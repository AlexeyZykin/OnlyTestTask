<?php

namespace model\dto;

readonly class LoginRequest {

    private string $phoneOrEmail;

    private string $password;

    private string $smartCaptchaToken;

    public function __construct(array $data) {
        $this->phoneOrEmail = trim(strtolower($data['phone_or_email'] ?? ''));
        $this->password =  trim($data['password'] ?? '');
        $this->smartCaptchaToken = $data['smart-token'] ?? '';
    }

    public function getPhoneOrEmail(): string {
        return $this->phoneOrEmail;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getSmartCaptchaToken(): string {
        return $this->smartCaptchaToken;
    }



}