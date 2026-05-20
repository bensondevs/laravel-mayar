<?php

declare(strict_types=1);

use Bensondevs\Mayar\SaaSMembership\SaaSMembership;
use Bensondevs\Mayar\Tests\Feature\SaaSMembership\SaaSMembershipFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('deactivates a saas license', function (): void {
    Http::fake([
        'https://api.mayar.club/saas/v1/license/deactivate' => Http::response(
            body: SaaSMembershipFixtures::successResponse(),
        ),
    ]);

    $result = SaaSMembership::deactivate(
        SaaSMembershipFixtures::licenseCode(),
        SaaSMembershipFixtures::productId(),
    );

    expect($result)->toBeTrue();

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/saas/v1/license/deactivate') {
            return false;
        }

        $body = $request->data();

        return $body['licenseCode'] === SaaSMembershipFixtures::licenseCode()
            && $body['productId'] === SaaSMembershipFixtures::productId();
    });
});

it('throws validation exception when deactivate license code is missing', function (): void {
    SaaSMembership::deactivate('', SaaSMembershipFixtures::productId());
})->throws(ValidationException::class);

it('throws validation exception when deactivate product id is missing', function (): void {
    SaaSMembership::deactivate(SaaSMembershipFixtures::licenseCode(), '');
})->throws(ValidationException::class);
