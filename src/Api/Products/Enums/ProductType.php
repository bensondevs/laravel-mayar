<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Api\Products\Enums;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum ProductType: string
{
    use EnumExtension;

    case GenericLink = 'generic_link';

    case PhysicalProduct = 'physical_product';

    case Event = 'event';

    case Webinar = 'webinar';

    case DigitalProduct = 'digital_product';

    case Coaching = 'coaching';

    case Course = 'course';

    case CohortBased = 'cohort_based';

    case Fundraising = 'fundraising';

    case Ebook = 'ebook';

    case Podcast = 'podcast';

    case Audiobook = 'audiobook';

    case Membership = 'membership';

    case Zakat = 'zakat';

    case Invoice = 'invoice';

    case Bundling = 'bundling';

    case Saas = 'saas';

    case PaymentRequest = 'payment_request';

    case Support = 'support';
}
