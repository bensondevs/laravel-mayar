<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Integration;

use Bensondevs\Mayar\Tests\TestCase;
use Dotenv\Dotenv;

abstract class IntegrationTestCase extends TestCase
{
    private static bool $dotenvLoaded = false;

    protected function defineEnvironment($app): void
    {
        self::loadPackageDotenv();

        $app['config']->set('mayar.api_key', env('MAYAR_API_KEY'));
        $app['config']->set('mayar.mode', env('MAYAR_MODE', 'sandbox'));
    }

    private static function loadPackageDotenv(): void
    {
        if (self::$dotenvLoaded) {
            return;
        }

        $envPath = dirname(__DIR__, 2);

        if (is_file($envPath . '/.env')) {
            Dotenv::createImmutable($envPath)->safeLoad();
        }

        self::$dotenvLoaded = true;
    }
}
