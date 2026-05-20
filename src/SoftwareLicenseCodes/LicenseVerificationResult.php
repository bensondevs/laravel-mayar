<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\SoftwareLicenseCodes;

final class LicenseVerificationResult
{
    /**
     * @param  array<string, mixed>|null  $licenseCode
     */
    public function __construct(
        public readonly bool $isLicenseActive,
        public readonly ?array $licenseCode = null,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromMayar(array $payload): self
    {
        $licenseCode = $payload['licenseCode'] ?? null;

        return new self(
            isLicenseActive: (bool) ($payload['isLicenseActive'] ?? false),
            licenseCode: is_array($licenseCode) ? $licenseCode : null,
        );
    }
}
