<?php

declare(strict_types=1);

namespace Application\Config\DatabaseConfig;

use Application\Config\ApplicationConfig\ApplicationConfig;
use Cycle\Database\Config;

final readonly class DatabaseConfig
{

    public static function config(): array
    {
        return [
            'default' => 'default',
            'databases' => [
                'default' => ['connection' => 'sqlite']
            ],
            'connections' => [
                'sqlite' => new Config\SQLiteDriverConfig(
                    connection: new Config\SQLite\FileConnectionConfig(
                        database: ApplicationConfig::baseDir() . '/database/database.sqlite'
                    ),
                    queryCache: true
                ),
            ]
        ];
    }
}