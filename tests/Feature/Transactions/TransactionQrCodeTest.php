<?php

declare(strict_types=1);

use Bensondevs\Mayar\Tests\Feature\Transactions\TransactionFixtures;
use Bensondevs\Mayar\Transactions\DynamicQrCodeResult;
use Bensondevs\Mayar\Transactions\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('creates a dynamic qr code', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/qrcode/create' => Http::response(
            body: TransactionFixtures::qrcodeCreateResponse(),
        ),
    ]);

    $result = Transaction::createDynamicQrCode(10000);

    expect($result)->toBeInstanceOf(DynamicQrCodeResult::class)
        ->and($result->url)->toContain('a30ed45f-976b-490f-b97c-72c90d1e8d9d')
        ->and($result->amount)->toBe(10000);

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/hl/v1/qrcode/create') {
            return false;
        }

        return $request->data()['amount'] === 10000;
    });
});

it('throws validation exception when qr code amount is invalid', function (): void {
    Transaction::createDynamicQrCode(0);
})->throws(ValidationException::class);
