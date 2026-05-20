<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Invoices;

use InvalidArgumentException;

final class InvoiceItem
{
    public function __construct(
        public readonly int $quantity,
        public readonly int $rate,
        public readonly string $description,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        foreach (['quantity', 'rate', 'description'] as $key) {
            if (! array_key_exists($key, $data)) {
                throw new InvalidArgumentException("Invoice item is missing required key [{$key}].");
            }
        }

        if (! is_numeric($data['quantity']) || ! is_numeric($data['rate'])) {
            throw new InvalidArgumentException('Invoice item quantity and rate must be numeric.');
        }

        if (! is_string($data['description'])) {
            throw new InvalidArgumentException('Invoice item description must be a string.');
        }

        return new self(
            quantity: (int) $data['quantity'],
            rate: (int) $data['rate'],
            description: $data['description'],
        );
    }

    /**
     * @return array{quantity: int, rate: int, description: string}
     */
    public function toArray(): array
    {
        return [
            'quantity' => $this->quantity,
            'rate' => $this->rate,
            'description' => $this->description,
        ];
    }
}
