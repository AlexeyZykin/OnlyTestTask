<?php

namespace core;

use config\Config;
use PDO;

class DbConnectionProvider {

    private static ?PDO $conn = null;

    private function __construct() {}

    public static function getConnection(string $dsn, string $username, string $password): PDO {
        if (!isset(self::$conn)) {
            self::$conn = new PDO(
                dsn: $dsn,
                username: $username,
                password: $password
            );
            self::$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$conn;
    }

}