<?php

namespace service;

use config\Config;
use exception\SmartCaptchaFailedException;
use model\LogLevel;
use function util\log;

/**
 * Класс для обращения к стороннему api - Yandex Smart Captcha
 */
class SmartCaptchaApi {

    private const string YANDEX_CAPTCHA_VALIDATION_URL = "https://smartcaptcha.cloud.yandex.ru/validate";

    public function __construct(private Config $config) {}

    /**
     * Проверка Yandex Smart Captcha.
     *
     * @param $token - Одноразоваый токен от клиента, приходящий после прохождения капчи
     * @param $ip - ip пользователя
     *
     * @throws SmartCaptchaFailedException
     */
    function checkCaptcha($token, $ip): bool {
        $args = [
            "secret" => $this->config->SMART_CAPTCHA_SERVER_KEY(),
            "token" => $token,
            "ip" => $ip,
        ];

        $curlOptions = [
            CURLOPT_URL => static::YANDEX_CAPTCHA_VALIDATION_URL,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => http_build_query($args),
            CURLOPT_SSL_OPTIONS => CURLSSLOPT_NATIVE_CA,
        ];

        $ch = curl_init();

        curl_setopt_array($ch, $curlOptions);

        $jsonResponse = curl_exec($ch);
        $response = json_decode($jsonResponse);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch) || $httpCode !== 200) {
            log(curl_error($ch), LogLevel::ERROR);
            throw new SmartCaptchaFailedException("Ошибка проверки Yandex SmartCaptcha");
        }

        if ($response->status == "failed") {
            log("Smart Captcha validation failed: $response->message", LogLevel::INFO);
            throw new SmartCaptchaFailedException("Пройдите проверку Yandex SmartCaptcha");
        }

        return $response->status == "ok";
    }

}