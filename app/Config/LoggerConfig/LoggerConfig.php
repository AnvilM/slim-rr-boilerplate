<?php

declare(strict_types=1);

namespace Application\Config\LoggerConfig;

use Application\Config\ApplicationConfig\ApplicationConfig;
use Application\Config\ApplicationConfig\ApplicationEnvironmentEnum;
use Monolog\Level;

final readonly class LoggerConfig
{
    public static function path(): string
    {
        return ApplicationConfig::baseDir() . '/logs/app.log';
    }


    public static function level(): Level
    {
        return ApplicationConfig::appEnv() === ApplicationEnvironmentEnum::Production ? Level::Info : Level::Debug;
    }
}