<?php

declare(strict_types=1);

use Bensondevs\Mayar\Exceptions\MayarException;
use Bensondevs\Mayar\Http\Authentication;

it('builds headers using the provided api key', function (): void {
    $headers = Authentication::headers('my-api-key');

    expect($headers)->toBe([
        'Authorization' => 'Bearer my-api-key',
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ]);
});

it('builds headers using configured api key when argument is null', function (): void {
    config()->set('mayar.api_key', 'config-key');

    $headers = Authentication::headers();

    expect($headers['Authorization'])->toBe('Bearer config-key');
});

it('throws when no api key is available', function (): void {
    config()->set('mayar.api_key', null);

    Authentication::headers();
})->throws(MayarException::class, 'Mayar API key is not configured.');
