<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\SoftwareLicenseCodes;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;

class SoftwareLicenseCodeEndpoint
{
    public function __construct(
        protected ?MayarClient $client = null,
    ) {}

    public function verify(): string
    {
        return $this->baseUrl() . '/license/verify';
    }

    protected function baseUrl(): string
    {
        return rtrim($this->client()->mode()->softwareBaseUrl(), '/');
    }

    protected function client(): MayarClient
    {
        return $this->client ?? Mayar::client();
    }
}
