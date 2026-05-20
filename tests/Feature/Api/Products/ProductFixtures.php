<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Feature\Api\Products;

final class ProductFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function productDetailResponse(): array
    {
        $path = __DIR__ . '/fixtures/product-detail-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function productDetailData(): array
    {
        return self::productDetailResponse()['data'];
    }

    public static function productDetailId(): string
    {
        return self::productDetailData()['id'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function productListResponse(): array
    {
        $path = __DIR__ . '/fixtures/product-list-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function productListData(): array
    {
        return self::productListResponse()['data'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function productListFirstItem(): array
    {
        return self::productListData()[0];
    }
}
