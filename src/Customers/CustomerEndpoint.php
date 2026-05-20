<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Customers;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;

class CustomerEndpoint
{
    public function __construct(
        protected ?MayarClient $client = null,
    ) {}

    public function index(): string
    {
        return $this->baseUrl() . '/customer';
    }

    public function detail(): string
    {
        return $this->baseUrl() . '/customer/detail';
    }

    public function create(): string
    {
        return $this->baseUrl() . '/customer/create';
    }

    public function update(): string
    {
        return $this->baseUrl() . '/customer/update';
    }

    public function portalLogin(): string
    {
        return $this->baseUrl() . '/customer/login/portal';
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
