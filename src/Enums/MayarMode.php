<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Enums;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum MayarMode: string
{
    use EnumExtension;

    case Sandbox = 'sandbox';

    case Production = 'production';

    public static function default(): static
    {
        return self::Sandbox;
    }

    public static function fromConfig(): self
    {
        return self::findOrDefault(config('mayar.mode'));
    }

    public function baseUrl(): string
    {
        return match ($this) {
            self::Sandbox => 'https://api.mayar.club/hl/v1',
            self::Production => 'https://api.mayar.id/hl/v1',
        };
    }

    public function softwareBaseUrl(): string
    {
        return match ($this) {
            self::Sandbox => 'https://api.mayar.club/software/v1',
            self::Production => 'https://api.mayar.id/software/v1',
        };
    }
}
