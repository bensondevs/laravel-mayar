<?php

declare(strict_types=1);

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Enums\MayarMode;
use Bensondevs\Mayar\Providers\MayarServiceProvider;

it('merges package configuration', function (): void {
    expect(config('mayar.api_key'))->toBe('test-api-key')
        ->and(config('mayar.mode'))->toBe('sandbox');
});

it('registers a mayar client bound to config', function (): void {
    $client = app(MayarClient::class);

    expect($client)->toBeInstanceOf(MayarClient::class)
        ->and($client->mode())->toBe(MayarMode::Sandbox);
});

it('declares publishable config', function (): void {
    $provider = new MayarServiceProvider(app());

    expect($provider::class)->toBe(MayarServiceProvider::class);
});
