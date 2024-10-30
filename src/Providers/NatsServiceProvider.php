<?php

namespace Seddighi78\NatsLaravel\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Seddighi78\NatsLaravel\Console\Commands\NatsSubscriberWork;
use Seddighi78\NatsLaravel\Factories\NatsClientFactory;
use Seddighi78\NatsLaravel\Factories\NatsClientFactoryInterface;

class NatsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/nats.php', 'nats'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/nats.php' => config_path('nats.php'),
        ]);

        App::bind(NatsClientFactoryInterface::class, NatsClientFactory::class);

        if ($this->app->runningInConsole()) {
            $this->commands([NatsSubscriberWork::class]);
        }
    }
}
