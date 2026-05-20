<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Invoices\Enums;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Invoice list sort / status filter values.
 *
 * Mayar docs list `close` as a sort value but the filter example uses `sort=closed`.
 */
enum InvoiceSort: string
{
    use EnumExtension;

    case Active = 'active';

    case Paid = 'paid';

    case Closed = 'closed';
}
