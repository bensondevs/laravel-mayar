<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Reviews;

use Bensondevs\Mayar\Models\MayarResource;
use Illuminate\Pagination\LengthAwarePaginator;

class Review extends MayarResource
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'createdAt' => 'integer',
            'updatedAt' => 'integer',
            'rating' => 'integer',
        ];
    }

    public static function query(): ReviewQuery
    {
        return new ReviewQuery(static::class);
    }

    /**
     * @return LengthAwarePaginator<int, Review>
     */
    public static function paginate(
        int $page = 1,
        int $perPage = 10,
    ): LengthAwarePaginator {
        return static::query()->paginate(page: $page, perPage: $perPage);
    }
}
