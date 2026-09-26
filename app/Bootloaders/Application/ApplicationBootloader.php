<?php

declare(strict_types=1);

namespace Application\Bootloaders\Application;

use Application\Bootloaders\BootloaderInterface;
use Application\Bootloaders\Context;
use Application\Config\ApplicationConfig\ApplicationConfig;
use DI\Container as DIContainer;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

/**
 * Bootloader that sets up the main web application.
 * Requires a DI container in the input context and produces a Slim app instance.
 *
 * @implements BootloaderInterface<array{container: DIContainer}, array{container: DIContainer, app: App<ContainerInterface>}>
 */
final readonly class ApplicationBootloader implements BootloaderInterface
{
    /**
     * Creates the Slim app instance using the container from the context.
     *
     * @param Context<array{container: DIContainer}> $context Context containing the DI container
     *
     * @return Context<array{container: DIContainer, app: App<ContainerInterface>}> Context enriched with the app instance
     */
    public static function boot(Context $context): Context
    {
        /** @var DIContainer $container */
        $container = $context->get('container');

        $app = AppFactory::createFromContainer($container);

        $app->addRoutingMiddleware();

        $app->addBodyParsingMiddleware();

        $app->addErrorMiddleware(
            ApplicationConfig::appDebug(),
            true,
            true
        );

        /** @var Context<array{container: DIContainer, app: App<ContainerInterface>}> $result */
        $result = $context->with([
            'app' => $app,
        ]);

        return $result;
    }
}