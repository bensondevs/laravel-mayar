<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Feature\Transactions;

final class TransactionFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function balanceResponse(): array
    {
        $path = __DIR__ . '/fixtures/balance-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function balanceData(): array
    {
        return self::balanceResponse()['data'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function dailyResponse(): array
    {
        $path = __DIR__ . '/fixtures/daily-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function dailyData(): array
    {
        return self::dailyResponse()['data'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function qrcodeCreateResponse(): array
    {
        $path = __DIR__ . '/fixtures/qrcode-create-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public static function qrcodeCreateData(): array
    {
        return self::qrcodeCreateResponse()['data'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function unpaidListResponse(): array
    {
        $path = __DIR__ . '/fixtures/unpaid-list-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function unpaidListData(): array
    {
        return self::unpaidListResponse()['data'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function unpaidListFirstItem(): array
    {
        return self::unpaidListData()[0];
    }
}
