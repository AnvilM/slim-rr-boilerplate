<?php

declare(strict_types=1);

namespace Application\Bootstrappers;

use Application\Config\ApplicationConfig\ApplicationConfig;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

final readonly class ApplicationBootstrapper
{
    /**
     * @return App<ContainerInterface>
     */
    public static function createApp(ContainerInterface $container): App
    {
        $app = AppFactory::createFromContainer(
            $container
        );

        $app->addRoutingMiddleware();

        $app->addBodyParsingMiddleware();

        $app->addErrorMiddleware(
            ApplicationConfig::displayErrorDetails(),
            true,
            true
        );

        return $app;
    }
}