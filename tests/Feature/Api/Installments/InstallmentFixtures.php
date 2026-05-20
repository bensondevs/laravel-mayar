<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Feature\Api\Installments;

final class InstallmentFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function installmentCreateResponse(): array
    {
        $path = __DIR__ . '/fixtures/installment-create-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function installmentCreateData(): array
    {
        return self::installmentCreateResponse()['data'];
    }

    public static function installmentCreateId(): string
    {
        return self::installmentCreateData()['id'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function installmentDetailResponse(): array
    {
        $path = __DIR__ . '/fixtures/installment-detail-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function installmentDetailData(): array
    {
        return self::installmentDetailResponse()['data'];
    }

    public static function installmentDetailId(): string
    {
        return self::installmentDetailData()['id'];
    }
}
