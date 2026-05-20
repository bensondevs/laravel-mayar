<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\PaymentRequests\Enums;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum PaymentRequestStatus: string
{
    use EnumExtension;

    case Active = 'active';

    case Paid = 'paid';

    case Closed = 'closed';
}
