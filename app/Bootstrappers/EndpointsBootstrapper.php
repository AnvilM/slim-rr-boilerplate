<?php

declare(strict_types=1);

namespace Application\Bootstrappers;

use Application\Endpoints\ApiEndpoints;
use Application\Endpoints\EndpointInterface;
use Slim\App;

final class EndpointsBootstrapper
{

    /**
     * @var EndpointInterface[]
     */
    private static array $endpoints = [
        ApiEndpoints::class
    ];
    

    public static function registerEndpoints(App $app): void
    {
        foreach (self::$endpoints as $endpoint) {
            $endpoint::register($app);
        }
    }
}