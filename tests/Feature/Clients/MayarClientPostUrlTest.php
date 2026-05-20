<?php

declare(strict_types=1);

use Bensondevs\Mayar\Clients\MayarClient;
use Illuminate\Support\Facades\Http;

it('posts to an absolute url via postUrl', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/invoice/create' => Http::response([
            'statusCode' => 200,
            'messages' => 'success',
            'data' => ['id' => 'new-invoice'],
        ]),
    ]);

    $response = app(MayarClient::class)->postUrl(
        'https://api.mayar.club/hl/v1/invoice/create',
        ['name' => 'test'],
    );

    expect($response['data']['id'])->toBe('new-invoice');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/invoice/create'
            && $request->method() === 'POST'
            && $request->data()['name'] === 'test';
    });
});
