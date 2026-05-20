<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Discounts\Enums;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum DiscountType: string
{
    use EnumExtension;

    case Monetary = 'monetary';

    case Percentage = 'percentage';
}
