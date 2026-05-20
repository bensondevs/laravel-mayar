<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Customers;

use Bensondevs\Mayar\Http\MayarPayload;
use Bensondevs\Mayar\Models\MayarResource;
use Illuminate\Pagination\LengthAwarePaginator;
use LogicException;

class Customer extends MayarResource
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'createdAt' => 'integer',
            'updatedAt' => 'integer',
        ];
    }

    public static function query(): CustomerQuery
    {
        return new CustomerQuery(static::class);
    }

    /**
     * @return LengthAwarePaginator<int, Customer>
     */
    public static function paginate(
        int $page = 1,
        int $perPage = 10,
    ): LengthAwarePaginator {
        return static::query()->paginate(page: $page, perPage: $perPage);
    }

    public static function findByEmail(string $email): ?static
    {
        return static::query()->findByEmail($email);
    }

    public static function findByEmailOrFail(string $email): static
    {
        return static::query()->findByEmailOrFail($email);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function updateEmail(array $attributes): bool
    {
        $payload = [
            'fromEmail' => $attributes['fromEmail'] ?? null,
            'toEmail' => $attributes['toEmail'] ?? null,
        ];

        CustomerValidator::validateForUpdateEmail($payload);

        $endpoint = new CustomerEndpoint;
        $response = static::mayarClient()->postUrl($endpoint->update(), $payload);

        $message = $response['messages'] ?? '';

        return MayarPayload::isOk($response)
            || $message === 'success'
            || $message === 'sukses';
    }

    public static function sendPortalMagicLink(string $email): PortalMagicLinkResult
    {
        $payload = ['email' => $email];

        CustomerValidator::validateForPortalLogin($payload);

        $endpoint = new CustomerEndpoint;
        $response = static::mayarClient()->postUrl($endpoint->portalLogin(), $payload);

        return PortalMagicLinkResult::fromMayar($response['data'] ?? []);
    }

    public function exists(): bool
    {
        return filled($this->getKey());
    }

    public function isNew(): bool
    {
        return ! $this->exists();
    }

    public function save(): static
    {
        if ($this->exists()) {
            throw new LogicException('Customer cannot be updated via the Mayar API.');
        }

        $payload = $this->toCreatePayload();

        CustomerValidator::validateForCreate($payload);

        $endpoint = new CustomerEndpoint;
        $response = static::mayarClient()->postUrl($endpoint->create(), $payload);

        $this->fillFromMayar($response['data'] ?? []);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function fillFromMayar(array $payload): void
    {
        parent::fillFromMayar($payload);

        if (! array_key_exists('id', $this->attributes) && isset($this->attributes['customerId'])) {
            $this->setAttribute(key: 'id', value: (string) $this->attributes['customerId']);
        }
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
        ];
    }
}
