<?php

declare(strict_types=1);

namespace Bensondevs\Mayar;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Enums\MayarMode;
use InvalidArgumentException;

class Mayar
{
    public static function client(): MayarClient
    {
        return app(MayarClient::class);
    }

    public static function mode(MayarMode | string $mode): void
    {
        $resolved = $mode instanceof MayarMode
            ? $mode
            : MayarMode::find($mode);

        if ($resolved === null) {
            throw new InvalidArgumentException(
                'Invalid Mayar mode. Expected sandbox or production.',
            );
        }

        config(['mayar.mode' => $resolved->value]);
    }
}
