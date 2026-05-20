<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Http;

use Bensondevs\Mayar\Enums\MayarMode;

class Endpoint
{
    public function __construct(
        protected MayarMode $mode,
    ) {
    }

    public function url(string $path): string
    {
        return rtrim($this->mode->baseUrl(), '/').'/'.ltrim($path, '/');
    }

    public function customers(): string
    {
        return $this->url('customer');
    }

    public function customerByEmail(): string
    {
        return $this->url('customer/detail');
    }

    public function products(): string
    {
        return $this->url('product');
    }

    public function product(string $id): string
    {
        return $this->url('product/'.$id);
    }
}
