<?php

declare(strict_types=1);

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Enums\MayarMode;
use Bensondevs\Mayar\Exceptions\MayarRequestException;
use Illuminate\Support\Facades\Http;

it('fetches customer list from the sandbox api', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/customer*' => Http::response([
            'statusCode' => 200,
            'messages' => 'success',
            'hasMore' => false,
            'page' => 1,
            'pageSize' => 10,
            'data' => [
                ['id' => 'customer-1', 'email' => 'a@example.com', 'name' => 'Alice'],
            ],
        ]),
    ]);

    $response = app(MayarClient::class)->get('customer', ['page' => 1, 'pageSize' => 10]);

    expect($response['data'])->toHaveCount(1)
        ->and($response['data'][0]['email'])->toBe('a@example.com');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/customer?page=1&pageSize=10'
            && $request->hasHeader('Authorization', 'Bearer test-api-key');
    });
});

it('fetches product detail', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/product/prod-123' => Http::response([
            'statusCode' => 200,
            'messages' => 'success',
            'data' => [
                'id' => 'prod-123',
                'name' => 'Test Product',
            ],
        ]),
    ]);

    $client = app(MayarClient::class);

    $response = $client->getProduct('prod-123');

    expect($response['data']['name'])->toBe('Test Product');
});

it('throws when the api returns unauthorized', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/customer*' => Http::response([
            'statusCode' => 401,
            'messages' => 'Unauthorized',
        ], 401),
    ]);

    app(MayarClient::class)->get('customer');
})->throws(MayarRequestException::class);

it('uses production base url when mode is production', function (): void {
    config(['mayar.mode' => 'production']);

    Http::fake([
        'https://api.mayar.id/hl/v1/customer*' => Http::response([
            'statusCode' => 200,
            'messages' => 'success',
            'data' => [],
        ]),
    ]);

    app(MayarClient::class)->get('customer');

    Http::assertSent(fn ($request): bool => str_starts_with($request->url(), 'https://api.mayar.id/hl/v1/customer'));
});
