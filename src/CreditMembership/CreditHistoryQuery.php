<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\CreditMembership;

use Bensondevs\Mayar\Models\MayarQuery;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends MayarQuery<CreditHistory>
 */
class CreditHistoryQuery extends MayarQuery
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, CreditHistory>
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

        CreditMembershipValidator::validateForPaginateHistory(array_merge($query, [
            'page' => $page,
            'limit' => $query['limit'] ?? $perPage,
        ]));

        $endpoint = new CreditMembershipEndpoint;

        return $this->paginateMayarList(
            url: $endpoint->paginateCreditHistory($identityId),
            page: $page,
            perPage: (int) ($query['limit'] ?? $perPage),
            query: $query,
        );
    }
}
