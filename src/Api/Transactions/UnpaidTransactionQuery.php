<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Transactions;

use Bensondevs\Mayar\Models\MayarQuery;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends MayarQuery<UnpaidTransaction>
 */
class UnpaidTransactionQuery extends MayarQuery
{
    /**
     * @return LengthAwarePaginator<int, UnpaidTransaction>
     */
    public function paginate(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        $endpoint = new TransactionEndpoint;

        return $this->paginateMayarList(
            url: $endpoint->unpaid(),
            page: $page,
            perPage: $perPage,
        );
    }
}
