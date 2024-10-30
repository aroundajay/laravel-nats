<?php

namespace Seddighi78\LaravelNats\Factories;

use Basis\Nats\Client;

interface NatsClientFactoryInterface
{
    public function getClient($connection = 'default'): Client;
}