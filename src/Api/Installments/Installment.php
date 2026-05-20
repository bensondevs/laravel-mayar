<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Installments;

use Bensondevs\Mayar\Models\MayarResource;
use LogicException;

class Installment extends MayarResource
{
    /**
     * @var array{description: string, interest: int, tenure: int, dueDate: int}|null
     */
    protected ?array $installmentTerms = null;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'createdAt' => 'integer',
            'dueDate' => 'integer',
            'tenure' => 'integer',
            'interest' => 'integer',
            'updatedAt' => 'integer',
        ];
    }

    public static function query(): InstallmentQuery
    {
        return new InstallmentQuery(static::class);
    }

    public static function find(mixed $id): ?static
    {
        return static::query()->find((string) $id);
    }

    public static function findOrFail(mixed $id): static
    {
        return static::query()->findOrFail((string) $id);
    }

    public function exists(): bool
    {
        return filled($this->getKey());
    }

    public function isNew(): bool
    {
        return ! $this->exists();
    }

    public function setInstallment(InstallmentTerms | array $terms): static
    {
        $this->installmentTerms = $this->normalizeInstallmentTerms($terms)->toArray();

        return $this;
    }

    /**
     * @return array{description: string, interest: int, tenure: int, dueDate: int}|null
     */
    public function getInstallment(): ?array
    {
        if ($this->installmentTerms !== null) {
            return $this->installmentTerms;
        }

        $attributeTerms = $this->attributes['installment'] ?? null;

        if (! is_array($attributeTerms)) {
            return null;
        }

        return $this->normalizeInstallmentTerms($attributeTerms)->toArray();
    }

    public function save(): static
    {
        if ($this->exists()) {
            throw new LogicException('Installment cannot be updated via the Mayar API.');
        }

        $payload = $this->toCreatePayload();

        InstallmentValidator::validateForCreate($payload);

        $endpoint = new InstallmentEndpoint;
        $response = static::mayarClient()->postUrl($endpoint->create(), $payload);

        $this->fillFromMayar($response['data'] ?? []);

        return $this;
    }

    public function refresh(): static
    {
        $fresh = static::query()->findOrFail((string) $this->getKey());

        $this->syncAttributes($fresh->getAttributes());

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function toCreatePayload(): array
    {
        return [
            'name' => $this->attributes['name'] ?? null,
            'email' => $this->attributes['email'] ?? null,
            'mobile' => $this->attributes['mobile'] ?? null,
            'amount' => $this->attributes['amount'] ?? null,
            'installment' => $this->getInstallment(),
        ];
    }

    protected function normalizeInstallmentTerms(InstallmentTerms | array $terms): InstallmentTerms
    {
        if ($terms instanceof InstallmentTerms) {
            return $terms;
        }

        return InstallmentTerms::fromArray($terms);
    }
}
