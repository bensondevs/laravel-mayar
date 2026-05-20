<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\CreditBasedProduct\CreditUsageHistory;
use Bensondevs\Mayar\Tests\Feature\Api\CreditBasedProduct\CreditBasedProductFixtures;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('paginates customer credit history without membership tier filter', function (): void {
    Http::fake([
        'https://api.mayar.club/credit/v1/credit/customer/paginate-credit-history/*' => Http::response(
            body: CreditBasedProductFixtures::paginateHistoryResponse(),
        ),
    ]);

    $paginator = CreditUsageHistory::paginate(
        identityId: CreditBasedProductFixtures::customerIdForHistory(),
        page: 1,
        perPage: 5,
        filters: [
            'productId' => CreditBasedProductFixtures::productId(),
        ],
    );

    $first = $paginator->first();

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginator)->toHaveCount(1)
        ->and($paginator->total())->toBe(10)
        ->and($first)->toBeInstanceOf(CreditUsageHistory::class)
        ->and($first->id)->toBe('68a84724c8d5f89bdeae7346')
        ->and($first->amount)->toBe(-100000)
        ->and($first->walletType)->toBe('MAIN');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/credit/v1/credit/customer/paginate-credit-history/' . CreditBasedProductFixtures::customerIdForHistory() . '?productId=' . CreditBasedProductFixtures::productId() . '&limit=5&page=1&pageSize=5';
    });
});

it('throws validation exception when product id is missing for credit usage history', function (): void {
    CreditUsageHistory::paginate(
        identityId: CreditBasedProductFixtures::customerIdForHistory(),
        page: 1,
        perPage: 5,
        filters: [],
    );
})->throws(ValidationException::class);
