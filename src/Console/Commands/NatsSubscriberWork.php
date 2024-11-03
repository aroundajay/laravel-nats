<?php

namespace Seddighi78\LaravelNats\Console\Commands;

use Illuminate\Console\Command;
use Seddighi78\LaravelNats\Events\MessageReceived;
use Seddighi78\LaravelNats\Factories\NatsClientFactoryInterface;

class NatsSubscriberWork extends Command
{
    protected $signature = 'nats:subscriber:work {subject} {--connection=default}';
    protected $description = 'Run worker for subscribe on nats';

    public function handle(): void
    {
        $subject = $this->argument('subject');
        $connection = $this->option('connection');

        $client = app(NatsClientFactoryInterface::class)->getClient($connection);
        $client->subscribe($subject, function ($message) {
            $this->info("received message from subject [$subject] message: $message");
            MessageReceived::dispatch($subject, $message);
        });

        $this->output->info("start processing message from [$subject] subject and connection [$connection] ...");

        while(true) {
            $client->process();
        }
    }
}
