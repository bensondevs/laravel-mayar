<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\CreditMembership\CreditMembership;
use Bensondevs\Mayar\Tests\Feature\CreditMembership\CreditMembershipFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('spends customer credit', function (): void {
    Http::fake([
        'https://api.mayar.club/credit/v1/credit/customer/spend' => Http::response(
            body: CreditMembershipFixtures::spendSuccessResponse(),
        ),
    ]);

    $result = CreditMembership::spend([
        'productId' => CreditMembershipFixtures::productId(),
        'membershipTierId' => CreditMembershipFixtures::membershipTierId(),
        'memberId' => CreditMembershipFixtures::memberId(),
        'amount' => 10,
    ]);

    expect($result)->toBeTrue();

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/credit/v1/credit/customer/spend') {
            return false;
        }

        $body = $request->data();

        return $body['productId'] === CreditMembershipFixtures::productId()
            && $body['membershipTierId'] === CreditMembershipFixtures::membershipTierId()
            && $body['memberId'] === CreditMembershipFixtures::memberId()
            && $body['amount'] === 10;
    });
});

it('throws validation exception when both member id and customer id are missing for spend', function (): void {
    CreditMembership::spend([
        'productId' => CreditMembershipFixtures::productId(),
        'membershipTierId' => CreditMembershipFixtures::membershipTierId(),
        'amount' => 10,
    ]);
})->throws(ValidationException::class);
