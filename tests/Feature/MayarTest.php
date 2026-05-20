<?php

declare(strict_types=1);

use Bensondevs\Mayar\Enums\MayarMode;
use Bensondevs\Mayar\Mayar;
use Illuminate\Support\Facades\Http;

it('updates config mode via Mayar::mode', function (): void {
    expect(config('mayar.mode'))->toBe('sandbox');

    Mayar::mode('production');

    expect(config('mayar.mode'))->toBe('production');
});

it('accepts MayarMode enum instances', function (): void {
    Mayar::mode(MayarMode::Sandbox);

    expect(config('mayar.mode'))->toBe('sandbox');
});

it('resolves production client after mode switch', function (): void {
    Http::fake([
        'https://api.mayar.id/hl/v1/product*' => Http::response([
            'statusCode' => 200,
            'messages' => 'success',
            'data' => [],
        ]),
    ]);

    Mayar::mode('production');
    Mayar::client()->get('product', ['page' => 1]);

    Http::assertSent(fn ($request): bool => str_starts_with($request->url(), 'https://api.mayar.id/hl/v1/product'));
});

it('rejects invalid mode values', function (): void {
    Mayar::mode('staging');
})->throws(InvalidArgumentException::class);
