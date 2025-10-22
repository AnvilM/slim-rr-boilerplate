<?php

declare(strict_types=1);

use Application\Bootstrappers\ProvidersBootstrapper;

require __DIR__ . '/vendor/autoload.php';

echo '<pre>';

print_r(ProvidersBootstrapper::getProviders());