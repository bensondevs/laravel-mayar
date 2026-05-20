<?php

declare(strict_types=1);

use Bensondevs\Mayar\Http\Controllers\MayarWebhookController;
use Illuminate\Support\Facades\Route;

$middleware = config('mayar.webhook.middleware', ['api']);

if (is_string($middleware)) {
    $middleware = [$middleware];
}

Route::post(config('mayar.webhook.path', 'webhooks/mayar'), MayarWebhookController::class)
    ->middleware($middleware)
    ->name((string) config('mayar.webhook.name', 'mayar.webhook'));
