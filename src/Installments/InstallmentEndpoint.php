<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Installments;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;

class InstallmentEndpoint
{
    public function __construct(
        protected ?MayarClient $client = null,
    ) {}

    public function create(): string
    {
        return $this->baseUrl() . '/installment/create';
    }

    public function show(string $id): string
    {
        return $this->baseUrl() . '/installment/' . $id;
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
