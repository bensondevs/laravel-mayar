<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Discounts;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;

class DiscountEndpoint
{
    public function __construct(
        protected ?MayarClient $client = null,
    ) {}

    public function create(): string
    {
        return $this->baseUrl() . '/coupon/create';
    }

    public function validate(): string
    {
        return $this->baseUrl() . '/coupon/validate';
    }

    public function show(string $id): string
    {
        return $this->baseUrl() . '/coupon/' . $id;
    }

    protected function baseUrl(): string
    {
        return rtrim($this->client()->mode()->baseUrl(), '/');
    }

    protected function client(): MayarClient
    {
        return $this->client ?? Mayar::client();
    }
}
