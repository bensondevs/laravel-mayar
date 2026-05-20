<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Invoices;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;

class InvoiceEndpoint
{
    public function __construct(
        protected ?MayarClient $client = null,
    ) {}

    public function index(): string
    {
        return $this->baseUrl() . '/invoice';
    }

    public function show(string $id): string
    {
        return $this->baseUrl() . '/invoice/' . $id;
    }

    public function create(): string
    {
        return $this->baseUrl() . '/invoice/create';
    }

    public function edit(): string
    {
        return $this->baseUrl() . '/invoice/edit';
    }

    public function close(string $id): string
    {
        return $this->baseUrl() . '/invoice/close/' . $id;
    }

    public function open(string $id): string
    {
        return $this->baseUrl() . '/invoice/open/' . $id;
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
