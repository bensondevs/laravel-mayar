<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\DataTransferObject;

use ArrayAccess;
use JsonSerializable;

/**
 * @implements ArrayAccess<string, mixed>
 */
final class MayarWebhookData implements ArrayAccess, JsonSerializable
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(
        private readonly array $attributes,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function fromArray(array $attributes): self
    {
        return new self($attributes);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function offsetExists(mixed $offset): bool
    {
        if (! is_string($offset)) {
            return false;
        }

        return $this->has($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (! is_string($offset)) {
            return null;
        }

        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // DTO is immutable by design.
    }

    public function offsetUnset(mixed $offset): void
    {
        // DTO is immutable by design.
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
