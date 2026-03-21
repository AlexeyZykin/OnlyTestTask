<?php

namespace validator;

use exception\ValidationException;
use model\dto\UpdateUserRequest;
use util\PhoneFormatUtils;

class UserValidator {

    /**
     * @throws ValidationException
     */
    public function validateUpdateRequest(UpdateUserRequest $request): void {
        $errors = [];

        $phone = trim($request->getPhone());
        if (!empty($phone) && !preg_match(PhoneFormatUtils::PHONE_REGEX, $phone)) {
            $errors['phone_error'] = "Телефон не соответсвует российскому формату";
        }

        $email = trim($request->getEmail());
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email_error'] = "Неверный формат email";
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }

}