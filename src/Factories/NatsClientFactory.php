<?php

namespace Seddighi78\NatsLaravel\Factories;

use Basis\Nats\Client;
use Basis\Nats\Configuration;
use Exception;

class NatsClientFactory implements ClientFactoryInterface
{
    public function getClient($connection = 'default'): Client
    {
        $parameters = config("nats.connections.$connection");

        if ($parameters === null) {
            throw new Exception("NATS Connection [$connection] not configured");
        }

        $configuration = new Configuration([
            'host' => $parameters['host'],
            'jwt' => $parameters['jwt'],
            'user' => $parameters['user'],
            'pass' => $parameters['pass'],
            'pedantic' => $parameters['pedantic'],
            'port' => $parameters['port'],
            'timeout' => $parameters['timeout'],
            'lang' => 'php',
            'reconnect' => true,
        ]);

        if (isset($parameters['delay'])) {
            $configuration->setDelay($parameters['delay']['seconds'], $parameters['delay']['mode']);
        }

        return new Client($configuration);
    }
}