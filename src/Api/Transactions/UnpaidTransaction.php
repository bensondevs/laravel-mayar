<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Transactions;

use Bensondevs\Mayar\Models\MayarResource;
use Illuminate\Pagination\LengthAwarePaginator;

class UnpaidTransaction extends MayarResource
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'createdAt' => 'integer',
        ];
    }

    public static function query(): UnpaidTransactionQuery
    {
        return new UnpaidTransactionQuery(static::class);
    }

    /**
     * @return LengthAwarePaginator<int, UnpaidTransaction>
     */
    public static function paginate(
        int $page = 1,
        int $perPage = 10,
    ): LengthAwarePaginator {
        return static::query()->paginate(page: $page, perPage: $perPage);
    }
}
