<?php

declare(strict_types=1);

use Bensondevs\Mayar\Tests\Feature\Api\Transactions\TransactionFixtures;
use Bensondevs\Mayar\Api\Transactions\Transaction;
use Illuminate\Support\Facades\Http;

it('returns daily transaction statistics as an array', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/transactions/daily' => Http::response(
            body: TransactionFixtures::dailyResponse(),
        ),
    ]);

    $daily = Transaction::daily();

    expect($daily)->toBe(TransactionFixtures::dailyData())
        ->and($daily['date'])->toBe('2026-05-08')
        ->and($daily['tpvCount'])->toBe(125000)
        ->and($daily['trxCount'])->toBe(10)
        ->and($daily)->not->toHaveKey('statusCode');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/transactions/daily';
    });
});
