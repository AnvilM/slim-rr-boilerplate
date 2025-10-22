<?php

declare(strict_types=1);

namespace Application\Endpoints;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;

final readonly class ApiEndpoints implements EndpointInterface
{
    public static function register(App $app): void
    {
        $app->get('/', function (RequestInterface $request, ResponseInterface $response) {
            $response->getBody()->write('Example response');
            return $response;
        });
    }
}