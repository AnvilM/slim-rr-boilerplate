<?php

declare(strict_types=1);

namespace Application\Bootloaders\Infrastructure;

use Application\Bootloaders\BootloaderInterface;
use Application\Bootloaders\Context;
use DI\Container as DIContainer;

/**
 * Bootloader responsible for setting up the dependency injection container.
 * Enriches the context with the fully configured DI container.
 *
 * @implements BootloaderInterface<array{}, array{container: DIContainer}>
 */
final readonly class InfrastructureBootloader implements BootloaderInterface
{
    /**
     * Creates and configures the DI container, then adds it to the context.
     *
     * @param Context<array{}> $context Incoming context
     *
     * @return Context<array{container: DIContainer}> Context enriched with the initialized container
     */
    public static function boot(Context $context): Context
    {
        $container = Container::createContainer(
            Providers::getProviders()
        );

        /** @var Context<array{container: DIContainer}> $result */
        $result = $context->with([
            'container' => $container,
        ]);

        return $result;
    }
}