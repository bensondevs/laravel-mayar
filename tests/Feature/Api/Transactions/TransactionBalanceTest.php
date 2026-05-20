<?php

declare(strict_types=1);

use Bensondevs\Mayar\Tests\Feature\Api\Transactions\TransactionFixtures;
use Bensondevs\Mayar\Api\Transactions\Transaction;
use Illuminate\Support\Facades\Http;

it('returns account balance data as an array', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/balance' => Http::response(
            body: TransactionFixtures::balanceResponse(),
        ),
    ]);

    $balance = Transaction::accountBalance();

    expect($balance)->toBe(TransactionFixtures::balanceData())
        ->and($balance)->toHaveKeys(['balanceActive', 'balancePending', 'balance'])
        ->and($balance)->not->toHaveKey('statusCode');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/balance';
    });
});
