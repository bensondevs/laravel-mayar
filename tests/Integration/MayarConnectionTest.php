<?php

declare(strict_types=1);

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Enums\MayarMode;
use Bensondevs\Mayar\Http\Authentication;
use Bensondevs\Mayar\Mayar;

it('resolves the client from the container with env credentials', function (): void {
    $client = app(MayarClient::class);
    $expectedMode = MayarMode::findOrDefault(config('mayar.mode'));
    $expectedHost = $expectedMode === MayarMode::Production
        ? 'https://api.mayar.id'
        : 'https://api.mayar.club';

    expect($client)->toBeInstanceOf(MayarClient::class)
        ->and($client->mode())->toBe($expectedMode)
        ->and($client->endpoint()->url('customer'))->toBe($expectedMode->baseUrl() . '/customer')
        ->and($client->endpoint()->url('customer'))->toStartWith($expectedHost);
});

it('authenticates with a bearer token from config', function (): void {
    $headers = Authentication::headers();

    expect($headers)->toHaveKey('Authorization')
        ->and($headers['Authorization'])->toStartWith('Bearer ')
        ->and(strlen($headers['Authorization']))->toBeGreaterThan(7);
});

it('fetches the customer list from the live api', function (): void {
    $response = Mayar::client()->get(uri: 'customer', query: ['page' => 1, 'pageSize' => 1]);

    expect($response['statusCode'])->toBe(200)
        ->and($response['messages'])->toBe('success')
        ->and($response['data'])->toBeArray();
});

it('fetches the product page from the live api', function (): void {
    $response = Mayar::client()->get(uri: 'product', query: ['page' => 1, 'pageSize' => 1]);

    expect($response['statusCode'])->toBe(200)
        ->and($response['messages'])->toBe('success')
        ->and($response['data'])->toBeArray();
});
