<?php

namespace util;

class SessionUtils {

    private function __construct() {}

    /**
     * Получить единожды данные из сессии. После получения данные из сессии очищаются.
     * @param $key - ключ для получения конкретных данных
    */
    public static function getOneTimeValue(string $key) {
        if (!isset($_SESSION[$key])) return null;

        $value = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $value;
    }

}