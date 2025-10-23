<?php

declare(strict_types=1);

namespace Application\Config\MigrationConfig;

use Application\Config\ApplicationConfig\ApplicationConfig;

final readonly class MigrationConfig
{
    /**
     * @return array{
     *     directory:string,
     *     table:string,
     *     safe:bool
     * }
     */
    public static function config(): array
    {
        return [
            'directory' => ApplicationConfig::baseDir() . '/database/migrations/',
            'table' => 'migrations',
            'safe' => true
        ];
    }
}