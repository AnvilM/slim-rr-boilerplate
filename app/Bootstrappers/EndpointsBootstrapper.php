<?php

declare(strict_types=1);

namespace Application\Bootstrappers;

use Application\Endpoints\ApiEndpoints;
use Application\Endpoints\EndpointInterface;
use Psr\Container\ContainerInterface;
use Slim\App;

final class EndpointsBootstrapper
{

    /** @var array<class-string<EndpointInterface>> */
    private static array $endpoints = [
        ApiEndpoints::class
    ];


    /** @param App<ContainerInterface> $app */
    public static function registerEndpoints(App $app): void
    {
        foreach (self::$endpoints as $endpoint) {
            $endpoint::register($app);
        }
    }
}