<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\PaymentRequests;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;

class PaymentRequestEndpoint
{
    public function __construct(
        protected ?MayarClient $client = null,
    ) {}

    public function index(): string
    {
        return $this->baseUrl() . '/payment';
    }

    public function show(string $id): string
    {
        return $this->baseUrl() . '/payment/' . $id;
    }

    public function create(): string
    {
        return $this->baseUrl() . '/payment/create';
    }

    public function edit(): string
    {
        return $this->baseUrl() . '/payment/edit';
    }

    public function close(string $id): string
    {
        return $this->baseUrl() . '/payment/close/' . $id;
    }

    public function open(string $id): string
    {
        return $this->baseUrl() . '/payment/open/' . $id;
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
