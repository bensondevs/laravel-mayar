<?php

declare(strict_types=1);

use Bensondevs\Mayar\Tests\Feature\Transactions\TransactionFixtures;
use Bensondevs\Mayar\Transactions\UnpaidTransaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

it('paginates unpaid transactions via the unpaid list endpoint', function (): void {
    Http::fake([
        'https://api.mayar.club/hl/v1/transactions/unpaid*' => Http::response(
            body: TransactionFixtures::unpaidListResponse(),
        ),
    ]);

    $paginator = UnpaidTransaction::paginate(page: 1, perPage: 10);

    $first = $paginator->first();

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginator)->toHaveCount(1)
        ->and($paginator->total())->toBe(278)
        ->and($first)->toBeInstanceOf(UnpaidTransaction::class)
        ->and($first->id)->toBe('040d5adb-1496-45de-8435-5cab16526a8c')
        ->and($first->type)->toBe('payment_request')
        ->and($first->amount)->toBe(100000)
        ->and($first->customer['name'])->toBe('Azumii')
        ->and($first->paymentUrl)->toContain('040d5adb-1496-45de-8435-5cab16526a8c');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/hl/v1/transactions/unpaid?page=1&pageSize=10';
    });
});
