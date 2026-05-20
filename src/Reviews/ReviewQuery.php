<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Reviews;

use Bensondevs\Mayar\Models\MayarQuery;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends MayarQuery<Review>
 */
class ReviewQuery extends MayarQuery
{
    /**
     * @return LengthAwarePaginator<int, Review>
     */
    public function paginate(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        $endpoint = new ReviewEndpoint;

        return $this->paginateMayarList(
            url: $endpoint->index(),
            page: $page,
            perPage: $perPage,
        );
    }
}
