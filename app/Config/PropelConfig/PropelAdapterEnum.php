<?php

declare(strict_types=1);

namespace Application\Config\DatabaseConfig;

use InvalidArgumentException;

enum DatabaseAdapterEnum: string
{
    case PostgreSQL = 'pgsql';

    case MySQL = 'mysql';

    case SQLite = 'sqlite';


    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'pgsql' => self::PostgreSQL,
            'mysql' => self::MySQL,
            'sqlite' => self::SQLite,
            default => throw new InvalidArgumentException("Unknown adapter: $value"),
        };
    }
}
