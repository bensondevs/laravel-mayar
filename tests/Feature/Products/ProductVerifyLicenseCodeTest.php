<?php

declare(strict_types=1);

use Bensondevs\Mayar\Products\Product;
use Bensondevs\Mayar\SoftwareLicenseCodes\LicenseVerificationResult;
use Bensondevs\Mayar\Tests\Feature\SoftwareLicenseCodes\SoftwareLicenseCodeFixtures;
use Illuminate\Support\Facades\Http;

it('verifies a license code via the product helper', function (): void {
    Http::fake([
        'https://api.mayar.club/software/v1/license/verify' => Http::response(
            body: SoftwareLicenseCodeFixtures::licenseVerifyResponse(),
        ),
    ]);

    $product = Product::fromMayar([
        'id' => SoftwareLicenseCodeFixtures::productId(),
        'name' => 'Licensed Software',
    ]);

    $result = $product->verifyLicenseCode(SoftwareLicenseCodeFixtures::licenseCode());

    expect($result)->toBeInstanceOf(LicenseVerificationResult::class)
        ->and($result->isLicenseActive)->toBeTrue()
        ->and($result->licenseCode['status'])->toBe('ACTIVE');

    Http::assertSent(function ($request): bool {
        if ($request->url() !== 'https://api.mayar.club/software/v1/license/verify') {
            return false;
        }

        $body = $request->data();

        return $body['licenseCode'] === SoftwareLicenseCodeFixtures::licenseCode()
            && $body['productId'] === SoftwareLicenseCodeFixtures::productId();
    });
});
