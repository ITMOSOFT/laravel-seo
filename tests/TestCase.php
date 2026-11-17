<?php

namespace ItmoSoft\Seo\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use ItmoSoft\Seo\SeoServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [SeoServiceProvider::class];
    }
}
