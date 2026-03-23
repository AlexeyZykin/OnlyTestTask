<?php

namespace core;

use config\Config;
use model\LogLevel;
use PDO;
use PDOException;
use function util\log;

readonly class DbInitializer {

    private function __construct() {}


    public static function init(PDO $conn): void {
        static::createUserSchema($conn);
        static::createUserTable($conn);
    }

    private static function createUserSchema(PDO $conn): void {
        try {
            $schemaName = Config::SCHEMA_NAME;

            $sql = "CREATE SCHEMA IF NOT EXISTS $schemaName";

            $conn->exec($sql);
        } catch (PDOException $e) {
            log($e->getMessage(), LogLevel::ERROR);
            throw $e;
        }
    }

    private static function createUserTable(PDO $conn): void {
        try {
            $tableName = Config::getFullUserTableName();

            $sql = "
            CREATE TABLE IF NOT EXISTS $tableName (
                id VARCHAR(255) PRIMARY KEY,
                login VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                phone VARCHAR(255) UNIQUE NOT NULL,
                password_hash VARCHAR(255) NOT NULL
            )
        ";

            $conn->exec($sql);
        } catch (PDOException $e) {
            log($e->getMessage(), LogLevel::ERROR);
            throw $e;
        }
    }

}