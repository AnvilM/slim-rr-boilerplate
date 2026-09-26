<?php

declare(strict_types=1);

namespace Application\Providers\ApplicationProviders;

use Application\Config\DatabaseConfig\DatabaseConfig;
use Application\Providers\ProviderInterface;
use Cycle\Database\Config\DatabaseConfig as CycleDatabaseConfig;
use Cycle\Database\DatabaseManager;

use function DI\factory;

/**
 * DBAL service provider.
 *
 * Registers the Cycle DatabaseManager in the DI container as a lazy
 * definition: the connections are only built the first time something
 * actually resolves DatabaseManager from the container.
 */
final readonly class DBALProvider implements ProviderInterface
{
    /**
     * @return array<string, mixed> Map of service identifiers to definitions
     */
    public static function register(): array
    {
        return [
            DatabaseManager::class => factory(static function (): DatabaseManager {
                return new DatabaseManager(
                    new CycleDatabaseConfig(
                        DatabaseConfig::config()
                    )
                );
            }),
        ];
    }
}
