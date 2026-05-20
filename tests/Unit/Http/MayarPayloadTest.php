<?php

declare(strict_types=1);

use Bensondevs\Mayar\Http\MayarPayload;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;

it('extracts data from a mayar payload', function (): void {
    $payload = [
        'statusCode' => 200,
        'messages' => 'success',
        'data' => ['balance' => 100],
    ];

    expect(MayarPayload::data($payload))->toBe(['balance' => 100]);
});

it('returns empty array when data is missing or invalid', function (): void {
    expect(MayarPayload::data([]))->toBe([])
        ->and(MayarPayload::data(['data' => 'invalid']))->toBe([]);
});

it('detects ok and not found status codes', function (): void {
    expect(MayarPayload::isOk(['statusCode' => HttpStatusCode::Ok->value]))->toBeTrue()
        ->and(MayarPayload::isNotFound(['statusCode' => HttpStatusCode::NotFound->value]))->toBeTrue()
        ->and(MayarPayload::isOk(['statusCode' => HttpStatusCode::NotFound->value]))->toBeFalse();
});
