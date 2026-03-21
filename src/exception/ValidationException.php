<?php

namespace exception;

use Exception;

class ValidationException extends Exception {

    private array $errors;

    private const BAD_REQUEST_CODE = 400;

    public function __construct(array $errors) {
        $this->errors = $errors;

        parent::__construct(reset($errors), self::BAD_REQUEST_CODE);
    }

    public function getErrors(): array {
        return $this->errors;
    }

}