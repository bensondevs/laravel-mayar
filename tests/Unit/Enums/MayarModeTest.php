<?php

declare(strict_types=1);

use Bensondevs\Mayar\Enums\MayarMode;

it('resolves modes via find', function (): void {
    expect(MayarMode::find('sandbox'))->toBe(MayarMode::Sandbox)
        ->and(MayarMode::find('production'))->toBe(MayarMode::Production)
        ->and(MayarMode::find('invalid'))->toBeNull();
});

it('defaults to sandbox', function (): void {
    expect(MayarMode::default())->toBe(MayarMode::Sandbox)
        ->and(MayarMode::findOrDefault('invalid'))->toBe(MayarMode::Sandbox);
});

it('reads mode from config', function (): void {
    config(['mayar.mode' => 'production']);

    expect(MayarMode::fromConfig())->toBe(MayarMode::Production);
});

it('maps each mode to the correct base url', function (): void {
    expect(MayarMode::Sandbox->baseUrl())->toBe('https://api.mayar.club/hl/v1')
        ->and(MayarMode::Production->baseUrl())->toBe('https://api.mayar.id/hl/v1');
});

it('exposes select options', function (): void {
    expect(MayarMode::options())->toBe([
        'sandbox' => 'Sandbox',
        'production' => 'Production',
    ]);
});
