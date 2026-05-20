<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\CreditBasedProduct;

use Bensondevs\Mayar\Models\MayarResource;
use Illuminate\Pagination\LengthAwarePaginator;

class CreditUsageHistory extends MayarResource
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    public static function query(): CreditUsageHistoryQuery
    {
        return new CreditUsageHistoryQuery(static::class);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, CreditUsageHistory>
     */
    public static function paginate(
        string $identityId,
        int $page = 1,
        int $perPage = 10,
        array $filters = [],
    ): LengthAwarePaginator {
        return static::query()->paginate(
            identityId: $identityId,
            page: $page,
            perPage: $perPage,
            filters: $filters,
        );
    }
}
