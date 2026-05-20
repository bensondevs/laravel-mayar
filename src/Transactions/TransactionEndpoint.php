<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Transactions;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;

class TransactionEndpoint
{
    public function __construct(
        protected ?MayarClient $client = null,
    ) {}

    public function balance(): string
    {
        return $this->baseUrl() . '/balance';
    }

    public function unpaid(): string
    {
        return $this->baseUrl() . '/transactions/unpaid';
    }

    public function daily(): string
    {
        return $this->baseUrl() . '/transactions/daily';
    }

    public function createQrCode(): string
    {
        return $this->baseUrl() . '/qrcode/create';
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
