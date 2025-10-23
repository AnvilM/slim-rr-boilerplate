<?php

declare(strict_types=1);

use Application\Kernel;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;

require __DIR__ . '/vendor/autoload.php';

$app = Kernel::createApp();


$worker = Worker::create();

$factory = new Psr17Factory();

$psr7 = new PSR7Worker($worker, $factory, $factory, $factory);

while (true) {
    try {
        $request = $psr7->waitRequest();
    } catch (Throwable $e) {
        $psr7->respond(new Response(400));
        continue;
    }

    try {
        $psr7->respond($app->handle($request));
    } catch (Throwable $e) {
        $worker->error((string)$e);
    }
}