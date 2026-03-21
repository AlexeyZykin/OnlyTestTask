<?php

namespace handler;

use exception\NotFoundException;
use model\LogLevel;
use Throwable;
use function util\log;

class GlobalExceptionHandler {

    public function handleError(int $errno, string $errstr, string $errfile = null, int $errline = null): void {
        $message = sprintf("Error [%d]: %s in %s on line %d", $errno, $errstr, $errfile, $errline);
        log($message, LogLevel::ERROR);

        http_response_code(500);

        echo "<h1>Ошибка на сервере</h1>";
        exit;
    }

    public function handleException(Throwable $e): void {
        if ($e instanceof NotFoundException) {
            $this->handleNotFoundException($e);
        } else {
            $this->handleUnknownException($e);
        }
    }

    private function handleNotFoundException(NotFoundException $e): void {
        log($e->getMessage(), LogLevel::WARN);

        http_response_code(404);

        echo "<h1>404 - Not Found</h1>";
        exit;
    }

    private function handleUnknownException(Throwable $ex): void {
        log($ex->getMessage(), LogLevel::ERROR);

        http_response_code(500);

        echo "<h1>Ошибка на сервере</h1>";
        exit;
    }

}