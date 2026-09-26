<?php

declare(strict_types=1);

namespace Application\Bootloaders\Infrastructure;

use Application\Providers\Registry;

/**
 * Providers bootstrapper.
 *
 * Responsible for collecting and merging provider definitions
 * for container initialization.
 */
final class Providers
{
    /**
     * Returns all provider definitions.
     *
     * Merges application-specific providers with global providers
     * and returns a flat associative array compatible with the DI container.
     *
     * Each provider may return plain instances, or lazy PHP-DI definitions
     * (DI\factory(), DI\autowire(), ...) — see ProviderInterface.
     *
     * @return array<string, mixed> Map of service identifiers to instances/definitions
     */
    public static function getProviders(): array
    {
        return array_merge(
            ...array_map(
                static fn (string $provider): array => $provider::register(),
                array_merge(Registry::$appProviders, Registry::$providers)
            )
        );
    }
}
