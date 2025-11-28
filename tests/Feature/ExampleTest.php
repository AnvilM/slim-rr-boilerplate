<?php

use Nyholm\Psr7\ServerRequest;

it('example', function () {
    expect($this->app->handle(new ServerRequest('GET', '/'))->getStatusCode())->toBe(200);
});
