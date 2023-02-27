<?php declare(strict_types=1);

use Soyhuce\ArrayFactory\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

uses()->beforeEach(function (): void {
    set_error_handler(function ($level, $message, $file = '', $line = 0): void {
        if (in_array($level, [E_DEPRECATED, E_USER_DEPRECATED], true) || (error_reporting() & $level)) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    });
})->in(__DIR__);
