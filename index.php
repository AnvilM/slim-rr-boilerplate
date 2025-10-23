<?php

declare(strict_types=1);

use Application\Kernel;

require __DIR__ . '/vendor/autoload.php';
echo '<pre>';

Kernel::createApp()->run(); 