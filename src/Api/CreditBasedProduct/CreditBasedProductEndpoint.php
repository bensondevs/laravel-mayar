<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\CreditBasedProduct;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;

class CreditBasedProductEndpoint
{
    public function __construct(
        protected ?MayarClient $client = null,
    ) {}

    public function customerBalance(): string
    {
        return $this->baseUrl() . '/credit/customer/balance';
    }

    public function paginateCreditHistory(string $identityId): string
    {
        return $this->baseUrl() . '/credit/customer/paginate-credit-history/' . $identityId;
    }

    public function spend(): string
    {
        return $this->baseUrl() . '/credit/customer/spend';
    }

    public function addCredit(): string
    {
        return $this->baseUrl() . '/credit/customer/add-credit';
    }

    public function registerCreditUsageCustomer(): string
    {
        return $this->baseUrl() . '/credit/credit-usage/customer/regist';
    }

    public function generateImmutableCheckout(): string
    {
        return $this->baseUrl() . '/credit/generate/immutable/checkout';
    }

    protected function baseUrl(): string
    {
        return rtrim($this->client()->mode()->creditBaseUrl(), '/');
    }

    protected function client(): MayarClient
    {
        return $this->client ?? Mayar::client();
    }
}
