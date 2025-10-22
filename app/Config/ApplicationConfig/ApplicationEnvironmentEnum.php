<?php

declare(strict_types=1);

namespace Application\Config\ApplicationConfig;

enum ApplicationEnvironmentEnum
{
    case Production;

    case Development;

    case Testing;
}
