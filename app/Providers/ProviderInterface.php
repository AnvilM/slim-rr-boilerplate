<?php

declare(strict_types=1);

namespace Application\Providers;

interface ProviderInterface
{
    public static function register(): array;
}