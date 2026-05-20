<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\CreditMembership\CreditHistory;
use Bensondevs\Mayar\Tests\Feature\Api\CreditMembership\CreditMembershipFixtures;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('paginates customer credit history', function (): void {
    Http::fake([
        'https://api.mayar.club/credit/v1/credit/customer/paginate-credit-history/*' => Http::response(
            body: CreditMembershipFixtures::paginateHistoryResponse(),
        ),
    ]);

    $paginator = CreditHistory::paginate(
        identityId: CreditMembershipFixtures::memberId(),
        page: 1,
        perPage: 5,
        filters: [
            'productId' => CreditMembershipFixtures::productId(),
            'membershipTierId' => CreditMembershipFixtures::membershipTierId(),
        ],
    );

    $first = $paginator->first();

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginator)->toHaveCount(1)
        ->and($paginator->total())->toBe(10)
        ->and($first)->toBeInstanceOf(CreditHistory::class)
        ->and($first->id)->toBe('68a84724c8d5f89bdeae7346')
        ->and($first->amount)->toBe(-100000)
        ->and($first->walletType)->toBe('MEMBERSHIP');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.mayar.club/credit/v1/credit/customer/paginate-credit-history/PQVS4KGY?productId=40f26fbe-f4d8-4693-975f-e6d105d291e6&membershipTierId=9bbbfa01-1bf8-4e4d-8470-cdf7066b6ea2&limit=5&page=1&pageSize=5';
    });
});

it('throws validation exception when product id is missing for history paginate', function (): void {
    CreditHistory::paginate(
        identityId: CreditMembershipFixtures::memberId(),
        page: 1,
        perPage: 5,
        filters: [],
    );
})->throws(ValidationException::class);
