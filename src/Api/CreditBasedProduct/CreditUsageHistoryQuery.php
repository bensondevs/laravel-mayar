<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\CreditBasedProduct;

use Bensondevs\Mayar\Models\MayarQuery;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends MayarQuery<CreditUsageHistory>
 */
class CreditUsageHistoryQuery extends MayarQuery
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, CreditUsageHistory>
     */
    public function paginate(
        string $identityId,
        int $page = 1,
        int $perPage = 10,
        array $filters = [],
    ): LengthAwarePaginator {
        $query = array_filter([
            'productId' => $filters['productId'] ?? null,
            'membershipTierId' => $filters['membershipTierId'] ?? null,
            'sortField' => $filters['sortField'] ?? null,
            'sortOrder' => $filters['sortOrder'] ?? null,
            'startDate' => $filters['startDate'] ?? null,
            'endDate' => $filters['endDate'] ?? null,
            'walletType' => $filters['walletType'] ?? null,
            'type' => $filters['type'] ?? null,
            'limit' => $filters['limit'] ?? $perPage,
        ], static fn (mixed $value): bool => $value !== null);

        CreditBasedProductValidator::validateForPaginateHistory(array_merge($query, [
            'page' => $page,
            'limit' => $query['limit'] ?? $perPage,
        ]));

        $endpoint = new CreditBasedProductEndpoint;

        return $this->paginateMayarList(
            url: $endpoint->paginateCreditHistory($identityId),
            page: $page,
            perPage: (int) ($query['limit'] ?? $perPage),
            query: $query,
        );
    }
}
