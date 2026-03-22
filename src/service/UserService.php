<?php

namespace service;

use exception\AlreadyExistsException;
use exception\UpdateFailedException;
use exception\UserNotFoundException;
use exception\ValidationException;
use model\dto\UpdateUserRequest;
use model\dto\UserDto;
use model\entity\UserEntity;
use repository\UserRepository;
use util\PhoneFormatUtils;

class UserService {

    public function __construct(private UserRepository $userRepository) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function findById($userId): UserDto {
        $userEntity = $this->userRepository->findById($userId);

        if (!isset($userEntity)) {
            throw new UserNotFoundException("Пользователь с данным id не найден: $userId");
        }

        return $this->toDto($userEntity);
    }


    /**
     * @throws UserNotFoundException
     * @throws UpdateFailedException
     * @throws AlreadyExistsException
     * @throws ValidationException
     */
    public function updateUser(UpdateUserRequest $request, $userId): UserDto {
        $userEntity = $this->userRepository->findById($userId);
        if (!isset($userEntity)) {
            throw new UserNotFoundException("Пользователь с данным id не найден: $userId");
        }

        $existingUser = $this->userRepository->findUser(
            $request->getEmail(),
            $request->getLogin(),
            empty($request->getPhone()) ? $request->getPhone() : PhoneFormatUtils::normalizePhone($request->getPhone())
        );
        if (isset($existingUser)) {
            $existingField = match (true) {
                $existingUser->getLogin() === $request->getLogin() => "Логин",
                $existingUser->getEmail() === $request->getEmail() => "Email",
                $existingUser->getPhone() === $request->getPhone() => "Телефон",
                default => "",
            };
            throw new AlreadyExistsException("Пользователь с такими данными уже существует: $existingField");
        }

        $passwordHash = empty($request->getPassword())
            ? $userEntity->getPasswordHash()
            : password_hash($request->getPassword(), PASSWORD_BCRYPT);
        $login = empty($request->getLogin()) ? $userEntity->getLogin() : $request->getLogin();
        $phone = empty($request->getPhone())
            ? $userEntity->getPhone()
            : PhoneFormatUtils::normalizePhone($request->getPhone());
        $email = empty($request->getEmail()) ? $userEntity->getEmail() : $request->getEmail();

        $updatedUser = new UserEntity(
            id: $userEntity->getId(),
            login: $login,
            phone: $phone,
            email: $email,
            passwordHash: $passwordHash,
        );

        $entity = $this->userRepository->update($updatedUser);

        if (!$entity) {
            throw new UpdateFailedException("Непредвиденная ошибка обновления. Пожалуйств попробуйте позже.");
        }

        return $this->toDto($entity);
    }


    private function toDto(UserEntity $entity): UserDto {
        return new UserDto(
            id: $entity->getId(),
            login: $entity->getLogin(),
            phone: $entity->getPhone(),
            email: $entity->getEmail()
        );
    }
}