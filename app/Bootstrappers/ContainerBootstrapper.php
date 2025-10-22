<?php

declare(strict_types=1);

namespace Application\Bootloaders;

use DI\Container;
use Psr\Container\ContainerInterface;

final readonly class ContainerBootloader
{
    public static function createContainer(): ContainerInterface
    {
        return new Container();
    }
}