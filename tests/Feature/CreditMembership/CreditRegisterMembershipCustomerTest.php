<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\CreditMembership\CreditMembership;
use Bensondevs\Mayar\Api\CreditMembership\RegisterMembershipCustomerResult;
use Bensondevs\Mayar\Tests\Feature\CreditMembership\CreditMembershipFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('registers a new membership customer', function (): void {
    Http::fake([
        'https://api.mayar.club/credit/v1/credit/membership/customer/regist' => Http::response(
            body: CreditMembershipFixtures::registerCustomerResponse(),
        ),
    ]);

    $result = CreditMembership::registerCustomer([
        'productId' => CreditMembershipFixtures::productId(),
        'membershipTierId' => CreditMembershipFixtures::membershipTierId(),
        'membershipMonthlyPeriod' => 1,
        'trialCredit' => 100,
        'customerInfo' => [
            'name' => 'memberTambahan',
            'email' => 'tambahan@gg.com',
            'mobile' => '08777777799',
        ],
    ]);

    expect($result)->toBeInstanceOf(RegisterMembershipCustomerResult::class)
        ->and($result->membershipCustomer['id'])->toBe('68ca1a1c-7ed8-47f3-a5c2-c5ff314dd750')
        ->and($result->membershipCustomer['paymentLink']['id'])->toBe(CreditMembershipFixtures::productId());

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/credit/v1/credit/membership/customer/regist') {
            return false;
        }

        $body = $request->data();

        return $body['productId'] === CreditMembershipFixtures::productId()
            && $body['membershipTierId'] === CreditMembershipFixtures::membershipTierId()
            && $body['membershipMonthlyPeriod'] === 1
            && $body['customerInfo']['email'] === 'tambahan@gg.com';
    });
});

it('throws validation exception when register payload is invalid', function (): void {
    CreditMembership::registerCustomer([
        'productId' => CreditMembershipFixtures::productId(),
        'membershipTierId' => CreditMembershipFixtures::membershipTierId(),
        'membershipMonthlyPeriod' => 2,
        'customerInfo' => [],
    ]);
})->throws(ValidationException::class);
