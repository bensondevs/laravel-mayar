<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\CreditMembership\CreditMembership;
use Bensondevs\Mayar\Api\CreditMembership\CustomerCreditBalanceResult;
use Bensondevs\Mayar\Tests\Feature\Api\CreditMembership\CreditMembershipFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('gets customer credit balance', function (): void {
    Http::fake([
        'https://api.mayar.club/credit/v1/credit/customer/balance*' => Http::response(
            body: CreditMembershipFixtures::customerBalanceResponse(),
        ),
    ]);

    $result = CreditMembership::customerBalance([
        'productId' => CreditMembershipFixtures::productId(),
        'membershipTierId' => CreditMembershipFixtures::membershipTierId(),
        'memberId' => CreditMembershipFixtures::memberId(),
    ]);

    expect($result)->toBeInstanceOf(CustomerCreditBalanceResult::class)
        ->and($result->customerBalance)->toBe(50990.0)
        ->and($result->customerBalanceMembership)->toBe(0.0)
        ->and($result->customerBalanceAddon)->toBe(50990.0);

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/credit/v1/credit/customer/balance?productId=40f26fbe-f4d8-4693-975f-e6d105d291e6&membershipTierId=9bbbfa01-1bf8-4e4d-8470-cdf7066b6ea2&memberId=PQVS4KGY') {
            return false;
        }

        return $request->method() === 'GET';
    });
});

it('throws validation exception when both member id and customer id are missing for balance', function (): void {
    CreditMembership::customerBalance([
        'productId' => CreditMembershipFixtures::productId(),
        'membershipTierId' => CreditMembershipFixtures::membershipTierId(),
    ]);
})->throws(ValidationException::class);
