<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Feature\Api\Discounts;

final class DiscountFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function discountCreateResponse(): array
    {
        $path = __DIR__ . '/fixtures/discount-create-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function discountCreateData(): array
    {
        return self::discountCreateResponse()['data'];
    }

    public static function discountCreateId(): string
    {
        return self::discountCreateData()['id'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function discountDetailResponse(): array
    {
        $path = __DIR__ . '/fixtures/discount-detail-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function discountDetailData(): array
    {
        return self::discountDetailResponse()['data'];
    }

    public static function discountDetailId(): string
    {
        return self::discountDetailData()['id'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function discountValidateResponse(): array
    {
        $path = __DIR__ . '/fixtures/discount-validate-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }
}
