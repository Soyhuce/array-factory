<?php declare(strict_types=1);

uses()->beforeEach(function (): void {
    set_error_handler(function ($level, $message, $file = '', $line = 0) {
        if (in_array($level, [E_DEPRECATED, E_USER_DEPRECATED]) || (error_reporting() & $level)) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    });
})->in(__DIR__);
