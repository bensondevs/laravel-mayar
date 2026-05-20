<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Models;

use BackedEnum;
use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Mayar;
use InvalidArgumentException;

abstract class MayarResource
{
    /**
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromMayar(array $payload): static
    {
        $resource = new static;

        $resource->fillFromMayar($payload);

        return $resource;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function fillFromMayar(array $payload): void
    {
        foreach ($payload as $key => $value) {
            $this->setAttribute(key: $key, value: $value);
        }
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function syncAttributes(array $attributes): void
    {
        $this->attributes = [];

        foreach ($attributes as $key => $value) {
            $this->setAttribute(key: $key, value: $value);
        }
    }

    public function getKey(): ?string
    {
        $key = $this->attributes['id'] ?? null;

        return $key === null ? null : (string) $key;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $key): mixed
    {
        if (! array_key_exists($key, $this->attributes)) {
            return null;
        }

        return $this->castAttribute(key: $key, value: $this->attributes[$key]);
    }

    public function setAttribute(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function __get(string $key): mixed
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->setAttribute(key: $key, value: $value);
    }

    public function __isset(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    protected static function mayarClient(): MayarClient
    {
        return Mayar::client();
    }

    protected function castAttribute(string $key, mixed $value): mixed
    {
        $casts = $this->casts();

        if (! isset($casts[$key])) {
            return $value;
        }

        $cast = $casts[$key];

        if ($cast === 'int' || $cast === 'integer') {
            return (int) $value;
        }

        if (is_string($cast) && is_subclass_of($cast, BackedEnum::class)) {
            if ($value instanceof $cast) {
                return $value;
            }

            if ($value === null) {
                return null;
            }

            if (! is_string($value) && ! is_int($value)) {
                throw new InvalidArgumentException(sprintf(
                    'Cannot cast attribute [%s] to enum [%s].',
                    $key,
                    $cast,
                ));
            }

            return $cast::from($value);
        }

        return $value;
    }
}
