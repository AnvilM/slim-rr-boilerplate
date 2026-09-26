<?php

declare(strict_types=1);

namespace Application\Bootloaders\Infrastructure;

use DI\Container as DiContainer;

/**
 * Container bootstrapper.
 *
 * Responsible for creating the DI container from a set of provider
 * definitions.
 */
final readonly class Container
{
    /**
     * Creates the DI container.
     *
     * @param array<string, mixed> $providers Provider definitions
     *
     * @return DiContainer Initialized DI container
     */
    public static function createContainer(array $providers): DiContainer
    {
        return new DiContainer($providers);
    }
}
