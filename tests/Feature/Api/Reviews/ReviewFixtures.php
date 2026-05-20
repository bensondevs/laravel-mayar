<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Tests\Feature\Api\Reviews;

final class ReviewFixtures
{
    /**
     * @return array<string, mixed>
     */
    public static function reviewListResponse(): array
    {
        $path = __DIR__ . '/fixtures/review-list-response.json';

        return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function reviewListData(): array
    {
        return self::reviewListResponse()['data'];
    }

    /**
     * @return array<string, mixed>
     */
    public static function reviewListFirstItem(): array
    {
        return self::reviewListData()[0];
    }
}
