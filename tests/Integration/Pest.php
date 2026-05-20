<?php

declare(strict_types=1);

require __DIR__ . '/helpers.php';

use Bensondevs\Mayar\Tests\Integration\IntegrationTestCase;

uses(IntegrationTestCase::class)->in(__DIR__);

beforeEach(function (): void {
    skipUnlessMayarConfigured();
});
