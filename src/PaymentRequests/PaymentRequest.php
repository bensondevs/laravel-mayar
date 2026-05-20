<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\PaymentRequests;

use Bensondevs\Mayar\Models\MayarResource;
use Bensondevs\Mayar\PaymentRequests\Enums\PaymentRequestStatus;
use DateTimeInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentRequest extends MayarResource
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'createdAt' => 'integer',
            'expiredAt' => 'integer',
        ];
    }

    public static function query(): PaymentRequestQuery
    {
        return new PaymentRequestQuery(static::class);
    }

    /**
     * @return LengthAwarePaginator<int, PaymentRequest>
     */
    public static function paginate(
        int $page = 1,
        int $perPage = 10,
    ): LengthAwarePaginator {
        return static::query()->paginate(page: $page, perPage: $perPage);
    }

    public static function status(PaymentRequestStatus | string $status): PaymentRequestQuery
    {
        return static::query()->status($status);
    }

    public static function find(mixed $id): ?static
    {
        return static::query()->find((string) $id);
    }

    public static function findOrFail(mixed $id): static
    {
        return static::query()->findOrFail((string) $id);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function update(array $attributes): static
    {
        $payload = self::buildEditPayloadFromArray($attributes);

        PaymentRequestValidator::validateForEdit($payload);

        $endpoint = new PaymentRequestEndpoint;
        $response = static::mayarClient()->postUrl($endpoint->edit(), $payload);

        $paymentRequest = static::fromMayar($response['data'] ?? []);

        if (isset($attributes['id'])) {
            $paymentRequest->setAttribute('id', (string) $attributes['id']);
        }

        return $paymentRequest;
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
        if ($this->isNew()) {
            return $this->performCreate();
        }

        return $this->performEdit();
    }

    public function close(): bool
    {
        $endpoint = new PaymentRequestEndpoint;
        $payload = static::mayarClient()->getUrl($endpoint->close((string) $this->getKey()));

        $success = ($payload['messages'] ?? '') === 'success';

        if ($success) {
            $this->setAttribute(key: 'status', value: PaymentRequestStatus::Closed->value);
        }

        return $success;
    }

    public function open(): bool
    {
        $endpoint = new PaymentRequestEndpoint;
        $payload = static::mayarClient()->getUrl($endpoint->open((string) $this->getKey()));

        $success = ($payload['messages'] ?? '') === 'success';

        if ($success) {
            $this->setAttribute(key: 'status', value: PaymentRequestStatus::Active->value);
        }

        return $success;
    }

    public function refresh(): static
    {
        $fresh = static::query()->findOrFail((string) $this->getKey());

        $this->syncAttributes($fresh->getAttributes());

        return $this;
    }

    protected function performCreate(): static
    {
        $payload = $this->toCreatePayload();

        PaymentRequestValidator::validateForCreate($payload);

        $endpoint = new PaymentRequestEndpoint;
        $response = static::mayarClient()->postUrl($endpoint->create(), $payload);

        $this->fillFromMayar($response['data'] ?? []);

        return $this;
    }

    protected function performEdit(): static
    {
        $payload = $this->toEditPayload();

        PaymentRequestValidator::validateForEdit($payload);

        $endpoint = new PaymentRequestEndpoint;
        $response = static::mayarClient()->postUrl($endpoint->edit(), $payload);

        $this->fillFromMayar($response['data'] ?? []);

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function toCreatePayload(): array
    {
        return $this->basePayload();
    }

    /**
     * @return array<string, mixed>
     */
    protected function toEditPayload(): array
    {
        return array_merge(['id' => $this->getKey()], $this->basePayload());
    }

    /**
     * @return array<string, mixed>
     */
    protected function basePayload(): array
    {
        return [
            'name' => $this->attributes['name'] ?? null,
            'email' => $this->attributes['email'] ?? null,
            'amount' => $this->attributes['amount'] ?? null,
            'mobile' => $this->attributes['mobile'] ?? null,
            'redirectUrl' => $this->attributes['redirectUrl'] ?? null,
            'description' => $this->attributes['description'] ?? null,
            'expiredAt' => $this->formatExpiredAt($this->attributes['expiredAt'] ?? null),
        ];
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    protected static function buildEditPayloadFromArray(array $attributes): array
    {
        $payload = [];

        if (isset($attributes['id'])) {
            $payload['id'] = (string) $attributes['id'];
        }

        foreach (['name', 'email', 'amount', 'mobile', 'redirectUrl', 'description', 'expiredAt'] as $key) {
            if (array_key_exists($key, $attributes)) {
                $payload[$key] = $attributes[$key];
            }
        }

        return $payload;
    }

    protected function formatExpiredAt(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d\TH:i:s.v\Z');
        }

        return (string) $value;
    }
}
