<?php

declare(strict_types=1);

namespace Application\Bootstrappers;


use Application\Providers\ApplicationProviders\DBALProvider;
use Application\Providers\ApplicationProviders\LoggerProvider;

final class ProvidersBootstrapper
{
    private static array $providers = [

    ];


    private static array $appProviders = [
        LoggerProvider::class,
        DBALProvider::class
    ];


    public static function getProviders(): array
    {
        return array_merge(
            ...array_map(
                fn(string $provider) => $provider::register(),
                self::$appProviders,
                self::$providers
            )
        );
    }
}