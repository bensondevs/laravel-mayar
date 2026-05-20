<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Webhooks;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;

class WebhookEndpoint
{
    public function __construct(
        protected ?MayarClient $client = null,
    ) {}

    public function history(): string
    {
        return $this->baseUrl() . '/webhook/history';
    }

    public function register(): string
    {
        return $this->baseUrl() . '/webhook/register';
    }

    public function test(): string
    {
        return $this->baseUrl() . '/webhook/test';
    }

    public function retry(): string
    {
        return $this->baseUrl() . '/webhook/retry';
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
