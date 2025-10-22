<?php

declare(strict_types=1);

namespace Application\Bootstrappers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;

final readonly class RoutesBootstrapper
{
    public function createRoutes(App $app): void
    {
        $app->get('/', function (RequestInterface $request, ResponseInterface $response) {
            $response->getBody()->write('aboba');
        });
    }
}