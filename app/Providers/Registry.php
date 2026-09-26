<?php

declare(strict_types=1);

namespace Application\Providers;

use Application\Providers\ApplicationProviders\DBALProvider;
use Application\Providers\ApplicationProviders\LoggerProvider;

/**
 * Registry of available providers.
 *
 * This class holds static lists of provider classes.
 */
final class Registry
{
    /**
     * List of general provider classes.
     *
     * @var array<class-string<ProviderInterface>> Array of provider class names
     */
    public static array $providers = [];

    /**
     * List of application-specific provider classes.
     *
     * @var array<class-string<ProviderInterface>> Array of application provider class names
     */
    public static array $appProviders = [
        LoggerProvider::class,
        DBALProvider::class,
    ];
}
