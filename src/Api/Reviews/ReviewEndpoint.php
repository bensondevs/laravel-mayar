<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Reviews;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;

class ReviewEndpoint
{
    public function __construct(
        protected ?MayarClient $client = null,
    ) {}

    public function index(): string
    {
        return $this->baseUrl() . '/reviews';
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
