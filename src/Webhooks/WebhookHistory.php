<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Webhooks;

use Bensondevs\Mayar\Models\MayarResource;
use Illuminate\Pagination\LengthAwarePaginator;

class WebhookHistory extends MayarResource
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'createdAt' => 'integer',
            'updatedAt' => 'integer',
        ];
    }

    public static function query(): WebhookHistoryQuery
    {
        return new WebhookHistoryQuery(static::class);
    }

    /**
     * @return LengthAwarePaginator<int, WebhookHistory>
     */
    public static function paginate(
        int $page = 1,
        int $perPage = 10,
    ): LengthAwarePaginator {
        return static::query()->paginate(page: $page, perPage: $perPage);
    }
}
