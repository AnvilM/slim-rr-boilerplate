<?php

declare(strict_types=1);

namespace Application\Config\ApplicationConfig;

use function Env\env;

final readonly class ApplicationConfig
{

    public static function baseDir(): string
    {
        return dirname(__DIR__, 3);
    }

    public static function appEnv(): ApplicationEnvironmentEnum
    {
        return match (env('APP_ENV')) {
            'production' => ApplicationEnvironmentEnum::Production,
            'testing' => ApplicationEnvironmentEnum::Testing,
            default => ApplicationEnvironmentEnum::Development
        };
    }

    public static function appDebug(): bool
    {
        return env("APP_DEBUG") ?? true;
    }

    public static function displayErrorDetails(): bool
    {
        return self::appDebug();
    }

}