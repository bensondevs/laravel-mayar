<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Products;

use Bensondevs\Mayar\Models\MayarResource;
use Bensondevs\Mayar\Api\Products\Enums\ProductType;
use Illuminate\Pagination\LengthAwarePaginator;

class Product extends MayarResource
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'type' => ProductType::class,
        ];
    }

    public static function query(): ProductQuery
    {
        return new ProductQuery(static::class);
    }

    public static function search(string $keyword): ProductQuery
    {
        return static::query()->search($keyword);
    }

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public static function paginate(
        int $page = 1,
        int $perPage = 10,
    ): LengthAwarePaginator {
        return static::query()->paginate(page: $page, perPage: $perPage);
    }

    public static function type(ProductType | string $type): ProductQuery
    {
        return static::query()->type($type);
    }

    public static function find(mixed $id): ?static
    {
        return static::query()->find((string) $id);
    }

    public static function findOrFail(mixed $id): static
    {
        return static::query()->findOrFail((string) $id);
    }

    public function close(): bool
    {
        $endpoint = new ProductEndpoint;
        $payload = static::mayarClient()->getUrl($endpoint->close($this->getKey()));

        $success = ($payload['messages'] ?? '') === 'success';

        if ($success) {
            $this->setAttribute(key: 'status', value: 'closed');
        }

        return $success;
    }

    public function reopen(): bool
    {
        $endpoint = new ProductEndpoint;
        $payload = static::mayarClient()->getUrl($endpoint->open($this->getKey()));

        $success = ($payload['messages'] ?? '') === 'success';

        if ($success) {
            $this->setAttribute(key: 'status', value: 'active');
        }

        return $success;
    }

    public function refresh(): static
    {
        $fresh = static::query()->findOrFail((string) $this->getKey());

        $this->syncAttributes($fresh->getAttributes());

        return $this;
    }
}
