<?php

namespace config;

class Config {

    public const string SCHEMA_NAME = "users";

    public const string TABLE_USER = "user";

    private array $data;

    private static ?self $instance = null;

    public function __construct() {
        $envFilePath = __DIR__ . "/../" . "/../" . ".env";

        if (file_exists($envFilePath)) {
            $this->data = parse_ini_file($envFilePath);
        }
    }

    public static function getInstance(): Config {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function DB_DSN(): string {
        $host = $this->data["DB_HOST"] ?? "localhost";
        $port = $this->data["DB_PORT"] ?? "5432";
        $dbname = $this->data["DB_NAME"] ?? "only-test-db";

        return "pgsql:host=$host;port=$port;dbname=$dbname";
    }

    public function DB_USERNAME(): string {
        return $this->data["DB_USERNAME"] ?? "postgres";
    }

    public function DB_PASSWORD(): string {
        return $this->data["DB_PASSWORD"] ?? "123#Qwer";
    }

    public function SMART_CAPTCHA_CLIENT_KEY(): string {
        return $this->data["SMART_CAPTCHA_CLIENT_KEY"] ?? "";
    }

    public function SMART_CAPTCHA_SERVER_KEY(): string {
        return $this->data["SMART_CAPTCHA_SERVER_KEY"] ?? "";
    }

    public static function getFullUserTableName(): string {
        return self::SCHEMA_NAME . '.' . self::TABLE_USER;
    }
}