<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\CreditMembership\AddCustomerCreditResult;
use Bensondevs\Mayar\Api\CreditMembership\CreditMembership;
use Bensondevs\Mayar\Tests\Feature\CreditMembership\CreditMembershipFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('adds customer credit', function (): void {
    Http::fake([
        'https://api.mayar.club/credit/v1/credit/customer/add-credit' => Http::response(
            body: CreditMembershipFixtures::addCreditResponse(),
        ),
    ]);

    $result = CreditMembership::addCredit([
        'productId' => CreditMembershipFixtures::productId(),
        'membershipTierId' => CreditMembershipFixtures::membershipTierId(),
        'customerId' => CreditMembershipFixtures::customerId(),
        'amount' => 100,
    ]);

    expect($result)->toBeInstanceOf(AddCustomerCreditResult::class)
        ->and($result->customerId)->toBe(CreditMembershipFixtures::customerId())
        ->and($result->productId)->toBe(CreditMembershipFixtures::productId())
        ->and($result->membershipTierId)->toBe(CreditMembershipFixtures::membershipTierId())
        ->and($result->amount)->toBe(100)
        ->and($result->customerBalance)->toBe(911090.0);

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/credit/v1/credit/customer/add-credit') {
            return false;
        }

        $body = $request->data();

        return $body['productId'] === CreditMembershipFixtures::productId()
            && $body['membershipTierId'] === CreditMembershipFixtures::membershipTierId()
            && $body['customerId'] === CreditMembershipFixtures::customerId()
            && $body['amount'] === 100;
    });
});

it('throws validation exception when both member id and customer id are missing for add credit', function (): void {
    CreditMembership::addCredit([
        'productId' => CreditMembershipFixtures::productId(),
        'membershipTierId' => CreditMembershipFixtures::membershipTierId(),
        'amount' => 100,
    ]);
})->throws(ValidationException::class);
