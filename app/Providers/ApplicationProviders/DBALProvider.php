<?php

declare(strict_types=1);

namespace Application\Providers\ApplicationProviders;

use Application\Config\DatabaseConfig\DatabaseConfig;
use Application\Providers\ProviderInterface;
use Cycle\Database\Config\DatabaseConfig as CycleDatabaseConfig;
use Cycle\Database\DatabaseManager;

final readonly class DBALProvider implements ProviderInterface
{
    public static function register(): array
    {
        return [DatabaseManager::class => new DatabaseManager(
            new CycleDatabaseConfig(
                DatabaseConfig::config()
            )
        )];
    }
}