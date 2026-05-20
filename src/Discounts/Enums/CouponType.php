<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Discounts\Enums;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum CouponType: string
{
    use EnumExtension;

    case Reusable = 'reusable';

    case Onetime = 'onetime';
}
