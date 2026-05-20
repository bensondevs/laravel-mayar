<?php

declare(strict_types=1);

use Bensondevs\Mayar\SaaSMembership\SaaSLicenseVerificationResult;
use Bensondevs\Mayar\SaaSMembership\SaaSMembership;
use Bensondevs\Mayar\Tests\Feature\SaaSMembership\SaaSMembershipFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('verifies a saas license subscription', function (): void {
    Http::fake([
        'https://api.mayar.club/saas/v1/license/verify' => Http::response(
            body: SaaSMembershipFixtures::verifyResponse(),
        ),
    ]);

    $result = SaaSMembership::verify(
        SaaSMembershipFixtures::licenseCode(),
        SaaSMembershipFixtures::productId(),
    );

    expect($result)->toBeInstanceOf(SaaSLicenseVerificationResult::class)
        ->and($result->isLicenseActive)->toBeTrue()
        ->and($result->licenseCode['licenseCode'])->toBe('LICENSECODE12345')
        ->and($result->licenseCode['membershipTierName'])->toBe('Master Black Belt Membership');

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/saas/v1/license/verify') {
            return false;
        }

        $body = $request->data();

        return $body['licenseCode'] === SaaSMembershipFixtures::licenseCode()
            && $body['productId'] === SaaSMembershipFixtures::productId();
    });
});

it('throws validation exception when verify license code is missing', function (): void {
    SaaSMembership::verify('', SaaSMembershipFixtures::productId());
})->throws(ValidationException::class);

it('throws validation exception when verify product id is missing', function (): void {
    SaaSMembership::verify(SaaSMembershipFixtures::licenseCode(), '');
})->throws(ValidationException::class);
