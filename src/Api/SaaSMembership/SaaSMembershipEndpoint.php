<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\SaaSMembership;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;

class SaaSMembershipEndpoint
{
    public function __construct(
        protected ?MayarClient $client = null,
    ) {}

    public function verify(): string
    {
        return $this->baseUrl() . '/license/verify';
    }

    public function activate(): string
    {
        return $this->baseUrl() . '/license/activate';
    }

    public function deactivate(): string
    {
        return $this->baseUrl() . '/license/deactivate';
    }

    protected function baseUrl(): string
    {
        return rtrim($this->client()->mode()->saasBaseUrl(), '/');
    }

    protected function client(): MayarClient
    {
        return $this->client ?? Mayar::client();
    }
}
