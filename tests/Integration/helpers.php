<?php

declare(strict_types=1);

function skipUnlessMayarConfigured(): void
{
    if (blank(config('mayar.api_key'))) {
        test()->markTestSkipped('MAYAR_API_KEY is not set in .env');
    }
}
