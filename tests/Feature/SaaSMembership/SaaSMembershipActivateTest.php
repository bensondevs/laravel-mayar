<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\SaaSMembership\SaaSMembership;
use Bensondevs\Mayar\Tests\Feature\SaaSMembership\SaaSMembershipFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('activates a saas license', function (): void {
    Http::fake([
        'https://api.mayar.club/saas/v1/license/activate' => Http::response(
            body: SaaSMembershipFixtures::successResponse(),
        ),
    ]);

    $result = SaaSMembership::activate(
        SaaSMembershipFixtures::licenseCode(),
        SaaSMembershipFixtures::productId(),
    );

    expect($result)->toBeTrue();

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/saas/v1/license/activate') {
            return false;
        }

        $body = $request->data();

        return $body['licenseCode'] === SaaSMembershipFixtures::licenseCode()
            && $body['productId'] === SaaSMembershipFixtures::productId();
    });
});

it('throws validation exception when activate license code is missing', function (): void {
    SaaSMembership::activate('', SaaSMembershipFixtures::productId());
})->throws(ValidationException::class);

it('throws validation exception when activate product id is missing', function (): void {
    SaaSMembership::activate(SaaSMembershipFixtures::licenseCode(), '');
})->throws(ValidationException::class);
