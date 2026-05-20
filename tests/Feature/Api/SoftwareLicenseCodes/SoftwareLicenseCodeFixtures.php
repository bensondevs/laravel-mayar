<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Feature\Api\SoftwareLicenseCodes;

final class SoftwareLicenseCodeFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function licenseVerifyResponse(): array
    {
        $path = __DIR__ . '/fixtures/license-verify-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    public static function licenseCode(): string
    {
        return 'LICENSECODE12345';
    }

    public static function productId(): string
    {
        return '84d1d247-a8b3-4c7d-96f0-cf276edb7c33';
    }
}
