<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Webhooks;

use Bensondevs\Mayar\Models\MayarQuery;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends MayarQuery<WebhookHistory>
 */
class WebhookHistoryQuery extends MayarQuery
{
    /**
     * @return LengthAwarePaginator<int, WebhookHistory>
     */
    public function paginate(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        $endpoint = new WebhookEndpoint;

        return $this->paginateMayarList(
            url: $endpoint->history(),
            page: $page,
            perPage: $perPage,
        );
    }
}
