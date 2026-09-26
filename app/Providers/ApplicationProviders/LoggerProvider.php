<?php

declare(strict_types=1);

namespace Application\Providers\ApplicationProviders;

use Application\Config\LoggerConfig\LoggerConfig;
use Application\Providers\ProviderInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

use function DI\factory;

/**
 * Logger service provider.
 *
 * Registers a PSR-3 compatible logger in the DI container as a lazy
 * definition: the Logger instance, its handler and its formatter are only
 * built the first time something actually resolves LoggerInterface from the
 * container.
 */
final readonly class LoggerProvider implements ProviderInterface
{
    /**
     * Registers services in the container.
     *
     * Returns an associative array where the key is a class or interface name
     * and the value is the corresponding lazy definition.
     *
     * @return array<string, mixed> Map of service identifiers to definitions
     */
    public static function register(): array
    {
        return [
            LoggerInterface::class => factory(static function (): LoggerInterface {
                return new Logger('app')
                    ->pushHandler(new RotatingFileHandler(
                        LoggerConfig::path(),
                        0,
                        LoggerConfig::level(),
                        true,
                        0777
                    )->setFormatter(
                        new LineFormatter(
                            null,
                            'Y-m-d H:i:s',
                            false,
                            true
                        )
                    ));
            }),
        ];
    }
}
