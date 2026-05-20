<?php

declare(strict_types=1);

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Exceptions\MayarRequestException;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;
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

    $response = app(MayarClient::class)->get(uri: 'customer', query: ['page' => 1, 'pageSize' => 10]);

    expect($response['data'])->toHaveCount(1)
        ->and($response['data'][0]['email'])->toBe('a@example.com');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/customer?page=1&pageSize=10'
            && $request->hasHeader('Authorization', 'Bearer test-api-key');
    });
});

it('throws when the api returns unauthorized', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/customer*' => Http::response(body: [
            'statusCode' => HttpStatusCode::Unauthorized->value,
            'messages' => 'Unauthorized',
        ], status: HttpStatusCode::Unauthorized->value),
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
