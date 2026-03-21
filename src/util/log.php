<?php

namespace util;

use model\LogLevel;

function log($message, $logLevel = LogLevel::INFO): void {
    $log = date('Y-m-d H:i:s') . '; ' . $logLevel->value. '; ' . $message;
    file_put_contents(__DIR__ . '/../../log/log.txt', $log . PHP_EOL, FILE_APPEND);
}