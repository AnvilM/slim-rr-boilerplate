<?php

declare(strict_types=1);

namespace Application;

use Application\Bootstrappers\ApplicationBootstrapper;
use Application\Bootstrappers\CommandsBootstrapper;
use Application\Bootstrappers\ContainerBootstrapper;
use Application\Bootstrappers\EndpointsBootstrapper;
use Application\Bootstrappers\ProvidersBootstrapper;
use Psr\Container\ContainerInterface;
use Slim\App;
use Symfony\Component\Console\Application as CliApp;

final readonly class Kernel
{

    /**
     * @return App<ContainerInterface>
     */
    public static function createApp(): App
    {
        $app = ApplicationBootstrapper::createApp(
            ContainerBootstrapper::createContainer(
                ProvidersBootstrapper::getProviders()
            )
        );

        EndpointsBootstrapper::registerEndpoints($app);

        return $app;
    }

    public static function createCliApp(): CliApp
    {
        $app = new CliApp();

        CommandsBootstrapper::registerCommands($app,
            ContainerBootstrapper::createContainer(
                ProvidersBootstrapper::getProviders()
            ));

        return $app;
    }
}