<?php

namespace Tests;

use Application\Kernel;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Slim\App;

abstract class TestCase extends BaseTestCase
{
    protected App $app;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app = Kernel::createApp();
    }

}
