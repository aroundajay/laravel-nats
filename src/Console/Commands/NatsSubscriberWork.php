<?php

namespace Seddighi78\LaravelNats\Console\Commands;

use Illuminate\Console\Command;
use Seddighi78\LaravelNats\Events\MessageReceived;
use Seddighi78\LaravelNats\Factories\NatsClientFactoryInterface;

class NatsSubscriberWork extends Command
{
    protected $signature = 'nats:subscriber:work {subject} {group?} {--connection=default}';
    protected $description = 'Run worker for subscribe on nats';

    public function handle(): void
    {
        $subject = $this->argument('subject');
        $group = $this->argument('group');
        $connection = $this->option('connection');

        $client = app(NatsClientFactoryInterface::class)->getClient($connection);

        if ($group) {
            $client->subscribeQueue($subject, $group, function ($message) use ($subject) {
                $this->info("Received message from subject [$subject]: $message");
                MessageReceived::dispatch($subject, $message);
            });
            $message = "Started processing messages from subject [$subject] in group [$group] with connection [$connection] ...";
        } else {
            $client->subscribe($subject, function ($message) use ($subject) {
                $this->info("Received message from subject [$subject]: $message");
                MessageReceived::dispatch($subject, $message);
            });
            $message = "Started processing messages from subject [$subject] with connection [$connection] ...";
        }

        $this->info($message);

        while (true) {
            $client->process();
        }
    }
}
