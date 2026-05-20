<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\SoftwareLicenseCodes;

use Bensondevs\Mayar\Mayar;

final class SoftwareLicenseCode
{
    public static function verify(string $licenseCode, string $productId): LicenseVerificationResult
    {
        $payload = [
            'licenseCode' => $licenseCode,
            'productId' => $productId,
        ];

        SoftwareLicenseCodeValidator::validateForVerify($payload);

        $endpoint = new SoftwareLicenseCodeEndpoint;
        $response = Mayar::client()->postUrl($endpoint->verify(), $payload);

        return LicenseVerificationResult::fromMayar($response);
    }
}
