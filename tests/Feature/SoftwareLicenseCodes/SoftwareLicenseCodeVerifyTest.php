<?php

declare(strict_types=1);

use Bensondevs\Mayar\Api\SoftwareLicenseCodes\LicenseVerificationResult;
use Bensondevs\Mayar\Api\SoftwareLicenseCodes\SoftwareLicenseCode;
use Bensondevs\Mayar\Tests\Feature\SoftwareLicenseCodes\SoftwareLicenseCodeFixtures;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

it('verifies a license code', function (): void {
    Http::fake([
        'https://api.mayar.club/software/v1/license/verify' => Http::response(
            body: SoftwareLicenseCodeFixtures::licenseVerifyResponse(),
        ),
    ]);

    $result = SoftwareLicenseCode::verify(
        SoftwareLicenseCodeFixtures::licenseCode(),
        SoftwareLicenseCodeFixtures::productId(),
    );

    expect($result)->toBeInstanceOf(LicenseVerificationResult::class)
        ->and($result->isLicenseActive)->toBeTrue()
        ->and($result->licenseCode['licenseCode'])->toBe('LICENSECODE12345')
        ->and($result->licenseCode['customerEmail'])->toBe('johndoe@gmail.com');

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/software/v1/license/verify') {
            return false;
        }

        $body = $request->data();

        return $body['licenseCode'] === SoftwareLicenseCodeFixtures::licenseCode()
            && $body['productId'] === SoftwareLicenseCodeFixtures::productId();
    });
});

it('throws validation exception when license code is missing', function (): void {
    SoftwareLicenseCode::verify('', SoftwareLicenseCodeFixtures::productId());
})->throws(ValidationException::class);

it('throws validation exception when product id is missing', function (): void {
    SoftwareLicenseCode::verify(SoftwareLicenseCodeFixtures::licenseCode(), '');
})->throws(ValidationException::class);
