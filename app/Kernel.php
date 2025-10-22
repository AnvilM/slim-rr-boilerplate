<?php

declare(strict_types=1);

namespace Application;

use Application\Bootstrappers\ApplicationBootstrapper;
use Application\Bootstrappers\ContainerBootstrapper;
use Application\Bootstrappers\EndpointsBootstrapper;
use Application\Bootstrappers\ProvidersBootstrapper;
use Slim\App;

final readonly class Kernel
{

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
}