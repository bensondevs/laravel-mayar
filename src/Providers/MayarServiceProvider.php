<?php

declare(strict_types=1);

namespace Bensondevs\Mayar\Providers;

use Bensondevs\Mayar\Clients\MayarClient;
use Bensondevs\Mayar\Enums\MayarMode;
use Illuminate\Support\ServiceProvider;

class MayarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__, 2) . '/config/mayar.php',
            'mayar',
        );

        $this->app->bind(MayarClient::class, function (): MayarClient {
            return new MayarClient(
                MayarMode::fromConfig(),
                config('mayar.api_key'),
            );
        });
    }

    public function boot(): void
    {
        if (config('mayar.webhook.enabled', true)) {
            $this->loadRoutesFrom(dirname(__DIR__, 2) . '/routes/mayar-webhook.php');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                dirname(__DIR__, 2) . '/config/mayar.php' => $this->app->configPath('mayar.php'),
            ], 'mayar-config');
        }
    }
}
