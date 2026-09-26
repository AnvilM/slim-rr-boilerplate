<?php

declare(strict_types=1);

namespace Application\Providers;

/**
 * Interface for a container provider.
 *
 * Providers define dependencies to be registered in a DI container.
 */
interface ProviderInterface
{
    /**
     * Registers provider definitions in the container.
     *
     * Returns an associative array where the key is the class or interface name
     * and the value is either a plain instance, or — preferably — a lazy PHP-DI
     * definition (DI\factory(), DI\autowire(), DI\create(), ...). Using a lazy
     * definition means the service is only actually built the first time
     * something asks the container for it, and factory closures may type-hint
     * the container itself (or any other registered service) to depend on
     * whatever other providers have registered, letting PHP-DI resolve the
     * order instead of the provider having to know it up front.
     *
     * @return array<string, mixed> Associative array of class/interface => instance/definition
     */
    public static function register(): array;
}
