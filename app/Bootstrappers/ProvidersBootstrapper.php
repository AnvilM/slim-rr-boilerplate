<?php

declare(strict_types=1);

namespace Application\Bootstrappers;


use Application\Providers\ApplicationProviders\DBALProvider;
use Application\Providers\ApplicationProviders\LoggerProvider;
use Application\Providers\ProviderInterface;

final class ProvidersBootstrapper
{

    /** @var array<class-string<ProviderInterface>> */
    private static array $providers = [

    ];

    /** @var array<class-string<ProviderInterface>> */
    private static array $appProviders = [
        LoggerProvider::class,
        DBALProvider::class
    ];

    /** @return array<string, mixed> */
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