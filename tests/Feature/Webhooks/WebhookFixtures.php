<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Feature\Webhooks;

final class WebhookFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function webhookHistoryListResponse(): array
    {
        $path = __DIR__ . '/fixtures/webhook-history-list-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function webhookHistoryListData(): array
    {
        return self::webhookHistoryListResponse()['data'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function webhookHistoryListFirstItem(): array
    {
        return self::webhookHistoryListData()[0];
    }

    public static function webhookHistoryListFirstId(): string
    {
        return self::webhookHistoryListFirstItem()['id'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function webhookSuccessResponse(): array
    {
        $path = __DIR__ . '/fixtures/webhook-success-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }
}
