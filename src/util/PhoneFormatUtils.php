<?php

namespace util;

use exception\ValidationException;

class PhoneFormatUtils {

    public const string PHONE_REGEX = "/^(?:\+?7|8)[\s\-]?\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2}$/";

    private function __construct() {}

    /**
     * Нормализует телефон в формат +7xxxxxxxxxx
     * @param $phone - номер телефона в любом валидном формате
     * @throws ValidationException неверный формат телефона
     */
    public static function normalizePhone(string $phone): string {
        if (!preg_match(static::PHONE_REGEX, $phone)) {
            $msg = "Формат входного номера телефона не соответствует российскому формату: $phone";
            throw new ValidationException([$msg]);
        }

        $digits = preg_replace('/\D/', '', $phone);

        if ($digits[0] === '8') {
            return '+7' . substr($digits, 1);
        }

        return '+' . $digits;
    }

}