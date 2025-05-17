<?php

namespace Juzaweb\Filemanager\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Juzaweb\Filemanager\Providers\FilemanagerServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            FilemanagerServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Thực hiện migrate các bảng cần thiết
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
