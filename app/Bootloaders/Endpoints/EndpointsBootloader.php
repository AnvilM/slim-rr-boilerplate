<?php

declare(strict_types=1);

namespace Application\Bootloaders\Endpoints;

use Application\Bootloaders\BootloaderInterface;
use Application\Bootloaders\Context;
use Application\Endpoints\ApiEndpoints;
use Application\Endpoints\EndpointInterface;
use DI\Container as DIContainer;
use Psr\Container\ContainerInterface;
use Slim\App;

/**
 * Bootloader that registers all HTTP endpoints on the Slim app.
 * Requires the app instance in the input context, and does not add anything
 * new to it — it only registers routes on the existing app as a side effect.
 *
 * @implements BootloaderInterface<array{container: DIContainer, app: App<ContainerInterface>}, array{container: DIContainer, app: App<ContainerInterface>}>
 */
final class EndpointsBootloader implements BootloaderInterface
{
    /**
     * List of endpoint classes to register.
     *
     * @var array<class-string<EndpointInterface>>
     */
    private static array $endpoints = [
        ApiEndpoints::class,
    ];

    /**
     * Registers every endpoint on the Slim app resolved from the context.
     *
     * @param Context<array{container: DIContainer, app: App<ContainerInterface>}> $context Context containing the app instance
     *
     * @return Context<array{container: DIContainer, app: App<ContainerInterface>}> Unchanged context
     */
    public static function boot(Context $context): Context
    {
        /** @var App<ContainerInterface> $app */
        $app = $context->get('app');

        foreach (self::$endpoints as $endpoint) {
            $endpoint::register($app);
        }

        return $context;
    }
}
