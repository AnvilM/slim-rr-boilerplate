<?php

declare(strict_types=1);

use Application\Kernel;

require dirname(__DIR__) . '/vendor/autoload.php';

Kernel::createCliApp()->run(); 