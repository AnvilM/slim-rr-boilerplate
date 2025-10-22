<?php

declare(strict_types=1);

namespace Application\Endpoints;

use Slim\App;

interface EndpointInterface
{
    public static function register(App $app): void;
}