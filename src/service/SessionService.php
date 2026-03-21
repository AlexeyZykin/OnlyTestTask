<?php

namespace service;

class SessionService {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set($key, $val): void {
        $_SESSION[$key] = $val;
    }

    public function get($key) {
        return $_SESSION[$key];
    }

    public function has($key): bool {
        return isset($_SESSION[$key]);
    }

    public function destroy(): void {
        session_destroy();
    }

    public function regenerateId(): void {
        session_regenerate_id(true);
    }



}