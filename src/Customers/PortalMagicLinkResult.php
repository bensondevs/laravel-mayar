<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Customers;

final class PortalMagicLinkResult
{
    public function __construct(
        public readonly string $url,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromMayar(array $payload): self
    {
        return new self(
            url: (string) ($payload['url'] ?? ''),
        );
    }
}
