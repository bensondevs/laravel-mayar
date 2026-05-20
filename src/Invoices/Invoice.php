<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Invoices;

use Bensondevs\Mayar\Invoices\Enums\InvoiceSort;
use Bensondevs\Mayar\Models\MayarResource;
use DateTimeInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class Invoice extends MayarResource
{
    /**
     * @var list<array{quantity: int, rate: int, description: string}>
     */
    protected array $items = [];

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

    public static function query(): InvoiceQuery
    {
        return new InvoiceQuery(static::class);
    }

    /**
     * @return LengthAwarePaginator<int, Invoice>
     */
    public static function paginate(
        int $page = 1,
        int $perPage = 10,
    ): LengthAwarePaginator {
        return static::query()->paginate(page: $page, perPage: $perPage);
    }

    public static function sort(InvoiceSort | string $sort): InvoiceQuery
    {
        return static::query()->sort($sort);
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

        InvoiceValidator::validateForEdit($payload);

        $endpoint = new InvoiceEndpoint;
        $response = static::mayarClient()->postUrl($endpoint->edit(), $payload);

        $invoice = static::fromMayar($response['data'] ?? []);

        if (isset($attributes['id'])) {
            $invoice->setAttribute('id', (string) $attributes['id']);
        }

        return $invoice;
    }

    public function exists(): bool
    {
        return filled($this->getKey());
    }

    public function isNew(): bool
    {
        return ! $this->exists();
    }

    public function addItem(InvoiceItem | array $item): static
    {
        $this->items[] = $this->normalizeItem($item)->toArray();

        return $this;
    }

    /**
     * @param  array<int, InvoiceItem|array<string, mixed>>  $items
     */
    public function setItems(array $items): static
    {
        $this->items = array_map(
            fn (InvoiceItem | array $item): array => $this->normalizeItem($item)->toArray(),
            $items,
        );

        return $this;
    }

    /**
     * @return list<array{quantity: int, rate: int, description: string}>
     */
    public function getItems(): array
    {
        if ($this->items !== []) {
            return $this->items;
        }

        $attributeItems = $this->attributes['items'] ?? null;

        if (! is_array($attributeItems)) {
            return [];
        }

        return array_map(
            fn (array $item): array => $this->normalizeItem($item)->toArray(),
            $attributeItems,
        );
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
        $endpoint = new InvoiceEndpoint;
        $payload = static::mayarClient()->getUrl($endpoint->close((string) $this->getKey()));

        $success = ($payload['messages'] ?? '') === 'success';

        if ($success) {
            $this->setAttribute(key: 'status', value: InvoiceSort::Closed->value);
        }

        return $success;
    }

    public function open(): bool
    {
        $endpoint = new InvoiceEndpoint;
        $payload = static::mayarClient()->getUrl($endpoint->open((string) $this->getKey()));

        $success = ($payload['messages'] ?? '') === 'success';

        if ($success) {
            $this->setAttribute(key: 'status', value: InvoiceSort::Active->value);
        }

        return $success;
    }

    public function refresh(): static
    {
        $fresh = static::query()->findOrFail((string) $this->getKey());

        $this->syncAttributes($fresh->getAttributes());
        $this->items = $fresh->getItems();

        return $this;
    }

    protected function performCreate(): static
    {
        $payload = $this->toCreatePayload();

        InvoiceValidator::validateForCreate($payload);

        $endpoint = new InvoiceEndpoint;
        $response = static::mayarClient()->postUrl($endpoint->create(), $payload);

        $this->fillFromMayar($response['data'] ?? []);

        return $this;
    }

    protected function performEdit(): static
    {
        $payload = $this->toEditPayload();

        InvoiceValidator::validateForEdit($payload);

        $endpoint = new InvoiceEndpoint;
        $response = static::mayarClient()->postUrl($endpoint->edit(), $payload);

        $this->fillFromMayar($response['data'] ?? []);

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
            'redirectUrl' => $this->attributes['redirectUrl'] ?? null,
            'description' => $this->attributes['description'] ?? null,
            'expiredAt' => $this->formatExpiredAt($this->attributes['expiredAt'] ?? null),
            'items' => $this->getItems(),
            'extraData' => $this->attributes['extraData'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function toEditPayload(): array
    {
        $payload = ['id' => $this->getKey()];

        foreach (['name', 'email', 'mobile', 'redirectUrl', 'description'] as $key) {
            if (array_key_exists($key, $this->attributes)) {
                $payload[$key] = $this->attributes[$key];
            }
        }

        if (array_key_exists('expiredAt', $this->attributes)) {
            $payload['expiredAt'] = $this->formatExpiredAt($this->attributes['expiredAt']);
        }

        if ($this->items !== []) {
            $payload['items'] = $this->getItems();
        }

        return $payload;
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

        foreach (['name', 'email', 'mobile', 'redirectUrl', 'description', 'expiredAt'] as $key) {
            if (array_key_exists($key, $attributes)) {
                $payload[$key] = $attributes[$key];
            }
        }

        if (isset($attributes['items']) && is_array($attributes['items'])) {
            $payload['items'] = array_map(
                fn (InvoiceItem | array $item): array => (new self)->normalizeItem($item)->toArray(),
                $attributes['items'],
            );
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

    protected function normalizeItem(InvoiceItem | array $item): InvoiceItem
    {
        if ($item instanceof InvoiceItem) {
            return $item;
        }

        return InvoiceItem::fromArray($item);
    }
}
