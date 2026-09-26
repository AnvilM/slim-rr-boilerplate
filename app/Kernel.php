<?php

declare(strict_types=1);

namespace Application;

use Application\Bootloaders\Application\ApplicationBootloader;
use Application\Bootloaders\Commands\CommandsBootloader;
use Application\Bootloaders\Console\ConsoleBootloader;
use Application\Bootloaders\Context;
use Application\Bootloaders\Endpoints\EndpointsBootloader;
use Application\Bootloaders\Environment\EnvironmentBootloader;
use Application\Bootloaders\Infrastructure\InfrastructureBootloader;
use DI\Container as DIContainer;
use Psr\Container\ContainerInterface;
use Slim\App;
use Symfony\Component\Console\Application as CliApp;

/**
 * Application kernel.
 *
 * Responsible for bootstrapping and building the web application and the
 * console application. Acts as the main entry point for both.
 */
final readonly class Kernel
{
    /**
     * Creates and configures the Slim web application instance.
     *
     * @return App<ContainerInterface> Fully configured Slim app instance
     */
    public static function createApp(): App
    {
        /** @var Context<array{}> $environmentContext */
        $environmentContext = EnvironmentBootloader::boot(new Context());

        /** @var Context<array{container: DIContainer}> $infrastructureContext */
        $infrastructureContext = InfrastructureBootloader::boot($environmentContext);

        /** @var Context<array{container: DIContainer, app: App<ContainerInterface>}> $applicationContext */
        $applicationContext = ApplicationBootloader::boot($infrastructureContext);

        /** @var Context<array{container: DIContainer, app: App<ContainerInterface>}> $endpointsContext */
        $endpointsContext = EndpointsBootloader::boot($applicationContext);

        return $endpointsContext->get('app');
    }

    /**
     * Creates and configures the console (CLI) application instance.
     *
     * @return CliApp Fully configured CLI app instance
     */
    public static function createCliApp(): CliApp
    {
        /** @var Context<array{}> $environmentContext */
        $environmentContext = EnvironmentBootloader::boot(new Context());

        /** @var Context<array{container: DIContainer}> $infrastructureContext */
        $infrastructureContext = InfrastructureBootloader::boot($environmentContext);

        /** @var Context<array{container: DIContainer, cliApp: CliApp}> $consoleContext */
        $consoleContext = ConsoleBootloader::boot($infrastructureContext);

        /** @var Context<array{container: DIContainer, cliApp: CliApp}> $commandsContext */
        $commandsContext = CommandsBootloader::boot($consoleContext);

        return $commandsContext->get('cliApp');
    }
}
