<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Products;

use Bensondevs\Mayar\Exceptions\MayarNotFoundException;
use Bensondevs\Mayar\Models\MayarQuery;
use Bensondevs\Mayar\Products\Enums\ProductType;
use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

/**
 * @extends MayarQuery<Product>
 */
class ProductQuery extends MayarQuery
{
    protected ?ProductType $filterType = null;

    protected ?string $searchKeyword = null;

    public function type(ProductType | string $type): static
    {
        if ($type instanceof ProductType) {
            $this->filterType = $type;
        } else {
            $resolved = ProductType::find($type);

            if ($resolved === null) {
                throw new InvalidArgumentException('Invalid product type: ' . $type);
            }

            $this->filterType = $resolved;
        }

        return $this;
    }

    public function search(string $keyword): static
    {
        $this->searchKeyword = $keyword;

        return $this;
    }

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function paginate(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        $query = [];

        if (filled($this->searchKeyword)) {
            $query['search'] = $this->searchKeyword;
        }

        return $this->paginateMayarList(
            url: $this->listUrl(),
            page: $page,
            perPage: $perPage,
            query: $query,
        );
    }

    public function find(string $id): ?Product
    {
        $endpoint = new ProductEndpoint;

        $payload = static::mayarClient()->getUrl($endpoint->show($id));

        if (HttpStatusCode::NotFound->is($payload['statusCode'] ?? null)) {
            return null;
        }

        $data = $payload['data'] ?? null;

        if (! is_array($data) || $data === []) {
            return null;
        }

        return Product::fromMayar($data);
    }

    public function findOrFail(string $id): Product
    {
        $product = $this->find($id);

        if ($product === null) {
            throw new MayarNotFoundException(modelClass: Product::class, ids: [$id]);
        }

        return $product;
    }

    protected function listUrl(): string
    {
        $endpoint = new ProductEndpoint;

        if (filled($this->filterType)) {
            return $endpoint->byType($this->filterType);
        }

        return $endpoint->index();
    }
}
