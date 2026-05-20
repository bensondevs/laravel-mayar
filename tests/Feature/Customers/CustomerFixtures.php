<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Feature\Customers;

final class CustomerFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function customerListResponse(): array
    {
        $path = __DIR__ . '/fixtures/customer-list-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function customerListData(): array
    {
        return self::customerListResponse()['data'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function customerListFirstItem(): array
    {
        return self::customerListData()[0];
    }

    /**
     * @return array<string, mixed>
     */
    public static function customerDetailResponse(): array
    {
        $path = __DIR__ . '/fixtures/customer-detail-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function customerDetailData(): array
    {
        return self::customerDetailResponse()['data'];
    }

    public static function customerDetailEmail(): string
    {
        return self::customerDetailData()['email'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function customerCreateResponse(): array
    {
        $path = __DIR__ . '/fixtures/customer-create-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function customerCreateData(): array
    {
        return self::customerCreateResponse()['data'];
    }

    public static function customerCreateId(): string
    {
        return self::customerCreateData()['customerId'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function customerUpdateResponse(): array
    {
        $path = __DIR__ . '/fixtures/customer-update-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function customerPortalLoginResponse(): array
    {
        $path = __DIR__ . '/fixtures/customer-portal-login-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }
}
