<?php

namespace Seddighi78\NatsLaravel\Console\Commands;

use Illuminate\Console\Command;
use Seddighi78\NatsLaravel\Events\MessageReceived;
use Seddighi78\NatsLaravel\Factories\NatsClientFactoryInterface;

class NatsSubscriberWork extends Command
{
    protected $signature = 'nats:subscriber:work {subject} {--connection=default}';
    protected $description = 'Run worker for subscribe on nats';

    public function handle(): void
    {
        $subject = $this->argument('subject');
        $connection = $this->option('connection');

        $client = app(NatsClientFactoryInterface::class)->getClient($connection);
        $client->subscribe($subject, fn ($message) => MessageReceived::dispatch($subject, $message));

        while(true) {
            $client->process();
        }
    }
}
