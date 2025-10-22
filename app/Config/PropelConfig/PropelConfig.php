<?php

declare(strict_types=1);

namespace Application\Config\DatabaseConfig;

use function Env\env;

final readonly class DatabaseConfig
{
    public static function driver(): string
    {
        return env('DB_DRIVER') ?? '127.0.0.1';
    }

    public static function host(): string
    {
        return env('DB_HOST') ?? '127.0.0.1';
    }

    public static function port(): string
    {
        return env('DB_PORT') ?? '5432';
    }
}