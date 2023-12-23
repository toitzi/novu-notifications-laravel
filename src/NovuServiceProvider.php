<?php

namespace NotificationChannels\Novu;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;

class NovuServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->when(NovuChannel::class)
            ->needs(GuzzleClient::class)
            ->give(function () {
                return new GuzzleClient();
            });

        /** @noinspection PhpUndefinedFunctionInspection */
        $this->publishes([
            realpath(__DIR__.'/../config/novu.php') => config_path('novu.php'),
        ], 'novu-notifications-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../config/novu.php'), 'novu-notifications');
    }
}
