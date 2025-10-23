<?php

declare(strict_types=1);

namespace Application\Endpoints;

use Psr\Container\ContainerInterface;
use Slim\App;

interface EndpointInterface
{
    /**
     * @param App<ContainerInterface> $app
     */
    public static function register(App $app): void;
}