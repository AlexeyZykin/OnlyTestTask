<?php

namespace repository;

use config\Config;
use model\entity\UserEntity;
use model\LogLevel;
use PDO;
use PDOException;
use function util\log;

class UserRepository {

    public function __construct(private readonly PDO $conn) {}


    public function findUser(string $email, string $login, string $phone): ?UserEntity {
        try {
            $tableName = Config::getFullUserTableName();

            $query = "
                SELECT * FROM $tableName WHERE email = :email OR login = :login OR phone = :phone
            ";

            $data = ["email" => $email, "login" => $login, "phone" => $phone];

            $stmt = $this->conn->prepare($query);
            $stmt->execute($data);

            $userArr = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$userArr) {
                return null;
            }

            return $this->toEntityFromArray($userArr);
        } catch (PDOException $e) {
            log($e->getMessage(), LogLevel::ERROR);
            throw $e;
        }
    }


    public function save(UserEntity $entity): ?UserEntity {
        try {
            $this->conn->beginTransaction();

            $tableName = Config::getFullUserTableName();

            $query = "
                INSERT INTO $tableName (id, login, email, phone, password_hash)
                VALUES (:id, :login, :email, :phone, :password_hash)
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'id' => $entity->getId(),
                'login' => $entity->getLogin(),
                'email' => $entity->getEmail(),
                'phone' => $entity->getPhone(),
                'password_hash' => $entity->getPasswordHash()
            ]);

            if ($stmt->rowCount() == 0) {
                return null;
            }

            $selectQuery = "SELECT * FROM $tableName WHERE id = :id";

            $stmt = $this->conn->prepare($selectQuery);
            $stmt->execute(['id' => $entity->getId()]);

            $this->conn->commit();

            return $this->toEntityFromArray($stmt->fetch(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            log($e->getMessage(), LogLevel::ERROR);
            $this->conn->rollBack();
            throw $e;
        }
    }


    public function findUserByPhoneOrEmail(string $phoneOrEmail): ?UserEntity {
        try {
            $tableName = Config::getFullUserTableName();

            $query = "SELECT * FROM $tableName WHERE phone = :phone OR email = :email";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(['phone' => $phoneOrEmail, 'email' => $phoneOrEmail]);

            $userArr = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$userArr) {
                return null;
            }

            return $this->toEntityFromArray($userArr);
        } catch (PDOException $e) {
            log($e->getMessage(), LogLevel::ERROR);
            throw $e;
        }
    }


    public function findById($userId): ?UserEntity {
        try {
            $tableName = Config::getFullUserTableName();

            $query = "SELECT * FROM $tableName WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(['id' => $userId]);

            $userArr = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$userArr) {
                return null;
            }

            return $this->toEntityFromArray($userArr);
        } catch (PDOException $e) {
            log($e->getMessage(), LogLevel::ERROR);
            throw $e;
        }
    }


    public function update(UserEntity $entity): ?UserEntity {
        try {
            $tableName = Config::getFullUserTableName();

            $query = "
                UPDATE $tableName
                SET login = :login, email = :email, phone = :phone, password_hash = :password_hash 
                WHERE id = :id    
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'id' => $entity->getId(),
                'login' => $entity->getLogin(),
                'email' => $entity->getEmail(),
                'phone' => $entity->getPhone(),
                'password_hash' => $entity->getPasswordHash()
            ]);

            if ($stmt->rowCount() == 0) {
                return null;
            }

            $selectQuery = "SELECT * FROM $tableName WHERE id = :id";
            $stmt = $this->conn->prepare($selectQuery);
            $stmt->execute(['id' => $entity->getId()]);

            return $this->toEntityFromArray($stmt->fetch(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            log($e->getMessage(), LogLevel::ERROR);
            throw $e;
        }
    }

    private function toEntityFromArray(array $userArr): UserEntity {
        return new UserEntity(
            id: $userArr['id'],
            login: $userArr['login'],
            phone: $userArr['phone'],
            email: $userArr['email'],
            passwordHash: $userArr['password_hash'],
        );
    }

}