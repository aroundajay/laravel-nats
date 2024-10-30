<?php

namespace Seddighi78\NatsLaravel\Factories;

use Basis\Nats\Client;

interface NatsClientFactoryInterface
{
    public function getClient($connection = 'default'): Client;
}