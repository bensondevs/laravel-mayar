<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Products;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;
use Bensondevs\Mayar\Api\Products\Enums\ProductType;
use InvalidArgumentException;

class ProductEndpoint
{
    public function __construct(
        protected ?MayarClient $client = null,
    ) {}

    public function index(): string
    {
        return $this->baseUrl() . '/product';
    }

    public function byType(ProductType | string $type): string
    {
        $resolved = $this->resolveType($type);

        return $this->baseUrl() . '/product/type/' . $resolved->value;
    }

    public function show(string $id): string
    {
        return $this->baseUrl() . '/product/' . $id;
    }

    public function close(string $id): string
    {
        return $this->baseUrl() . '/product/close/' . $id;
    }

    public function open(string $id): string
    {
        return $this->baseUrl() . '/product/open/' . $id;
    }

    protected function baseUrl(): string
    {
        return rtrim($this->client()->mode()->baseUrl(), '/');
    }

    protected function client(): MayarClient
    {
        return $this->client ?? Mayar::client();
    }

    protected function resolveType(ProductType | string $type): ProductType
    {
        if ($type instanceof ProductType) {
            return $type;
        }

        $resolved = ProductType::find($type);

        if ($resolved === null) {
            throw new InvalidArgumentException('Invalid product type: ' . $type);
        }

        return $resolved;
    }
}
