<?php

namespace Seddighi78\LaravelNats\Facades;

use Illuminate\Support\Facades\Facade;
use Seddighi78\LaravelNats\Factories\NatsClientFactoryInterface;

class Nats extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return NatsClientFactoryInterface::class;
    }
}
