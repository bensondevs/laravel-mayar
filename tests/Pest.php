<?php

declare(strict_types=1);

use Bensondevs\Mayar\Tests\Integration\IntegrationTestCase;
use Bensondevs\Mayar\Tests\TestCase;

require_once __DIR__ . '/Integration/helpers.php';

uses(TestCase::class)->in(__DIR__ . '/Feature', __DIR__ . '/Unit');
uses(IntegrationTestCase::class)->in(__DIR__ . '/Integration');
