<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Models;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Http\MayarPayload;
use Bensondevs\Mayar\Mayar;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * @template TResource of MayarResource
 */
abstract class MayarQuery
{
    /**
     * @param  class-string<TResource>  $modelClass
     */
    public function __construct(
        protected string $modelClass,
    ) {}

    /**
     * @return LengthAwarePaginator<int, TResource>
     */
    protected function paginateMayarList(
        string $url,
        int $page = 1,
        int $perPage = 10,
        array $query = [],
    ): LengthAwarePaginator {
        $query = array_merge($query, [
            'page' => $page,
            'pageSize' => $perPage,
        ]);

        $payload = static::mayarClient()->getUrl(url: $url, query: $query);

        /** @var array<int, array<string, mixed>> $rows */
        $rows = $payload['data'] ?? [];

        $items = Collection::make($rows)
            ->map(fn (array $row): MayarResource => $this->modelClass::fromMayar($row))
            ->all();

        $total = $this->resolveTotal(payload: $payload, perPage: $perPage);

        return new LengthAwarePaginator(
            items: $items,
            total: $total,
            perPage: $perPage,
            currentPage: $page,
        );
    }

    protected static function mayarClient(): MayarClient
    {
        return Mayar::client();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function isPayloadNotFound(array $payload): bool
    {
        return MayarPayload::isNotFound($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function resolveTotal(array $payload, int $perPage): int
    {
        if (isset($payload['total']) && is_numeric($payload['total'])) {
            return (int) $payload['total'];
        }

        $pageCount = (int) ($payload['pageCount'] ?? 0);
        $pageSize = (int) ($payload['pageSize'] ?? $perPage);

        if ($pageCount > 0 && $pageSize > 0) {
            return $pageCount * $pageSize;
        }

        return count($payload['data'] ?? []);
    }
}
