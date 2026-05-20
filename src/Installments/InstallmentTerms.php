<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Installments;

use InvalidArgumentException;

final class InstallmentTerms
{
    public function __construct(
        public readonly string $description,
        public readonly int $interest,
        public readonly int $tenure,
        public readonly int $dueDate,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        foreach (['description', 'interest', 'tenure', 'dueDate'] as $key) {
            if (! array_key_exists($key, $data)) {
                throw new InvalidArgumentException("Installment terms is missing required key [{$key}].");
            }
        }

        if (! is_string($data['description'])) {
            throw new InvalidArgumentException('Installment terms description must be a string.');
        }

        if (! is_numeric($data['interest']) || ! is_numeric($data['tenure']) || ! is_numeric($data['dueDate'])) {
            throw new InvalidArgumentException('Installment terms interest, tenure, and dueDate must be numeric.');
        }

        return new self(
            description: $data['description'],
            interest: (int) $data['interest'],
            tenure: (int) $data['tenure'],
            dueDate: (int) $data['dueDate'],
        );
    }

    /**
     * @return array{description: string, interest: int, tenure: int, dueDate: int}
     */
    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'interest' => $this->interest,
            'tenure' => $this->tenure,
            'dueDate' => $this->dueDate,
        ];
    }
}
