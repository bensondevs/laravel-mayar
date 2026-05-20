<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\CreditMembership;

use Bensondevs\Mayar\Models\MayarResource;
use Illuminate\Pagination\LengthAwarePaginator;

class CreditHistory extends MayarResource
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

    public static function query(): CreditHistoryQuery
    {
        return new CreditHistoryQuery(static::class);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, CreditHistory>
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
