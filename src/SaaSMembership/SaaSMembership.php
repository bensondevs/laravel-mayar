<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\SaaSMembership;

use Bensondevs\Mayar\Http\MayarPayload;
use Bensondevs\Mayar\Mayar;

final class SaaSMembership
{
    public static function verify(string $licenseCode, string $productId): SaaSLicenseVerificationResult
    {
        $payload = [
            'licenseCode' => $licenseCode,
            'productId' => $productId,
        ];

        SaaSMembershipValidator::validateLicensePayload($payload);

        $endpoint = new SaaSMembershipEndpoint;
        $response = Mayar::client()->postUrl($endpoint->verify(), $payload);

        return SaaSLicenseVerificationResult::fromMayar($response);
    }

    public static function activate(string $licenseCode, string $productId): bool
    {
        $payload = [
            'licenseCode' => $licenseCode,
            'productId' => $productId,
        ];

        SaaSMembershipValidator::validateLicensePayload($payload);

        $endpoint = new SaaSMembershipEndpoint;
        $response = Mayar::client()->postUrl($endpoint->activate(), $payload);

        return MayarPayload::isSuccessful($response);
    }

    public static function deactivate(string $licenseCode, string $productId): bool
    {
        $payload = [
            'licenseCode' => $licenseCode,
            'productId' => $productId,
        ];

        SaaSMembershipValidator::validateLicensePayload($payload);

        $endpoint = new SaaSMembershipEndpoint;
        $response = Mayar::client()->postUrl($endpoint->deactivate(), $payload);

        return MayarPayload::isSuccessful($response);
    }
}
