<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests;

use Bensondevs\Mayar\Providers\MayarServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            MayarServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('mayar.api_key', 'test-api-key');
        $app['config']->set('mayar.mode', 'sandbox');
    }
}
