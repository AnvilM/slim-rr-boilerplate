<?php

declare(strict_types=1);

namespace Application\Providers;

use Application\Config\LoggerConfig\LoggerConfig;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final readonly class LoggerProvider implements ProviderInterface
{

    public static function register(): array
    {
        return [LoggerInterface::class => new Logger('app')
            ->pushHandler(new RotatingFileHandler(
                LoggerConfig::path(),
                0,
                LoggerConfig::level(),
                true,
                0777
            )->setFormatter(new LineFormatter(
                    null,
                    'Y-m-d H:i:s',
                    false,
                    true
                )
            ))];
    }
}