# Laravel-Nats
This package provides an observer for the Laravel Nats push-based event system that uses Laravel events. it uses https://github.com/basis-company/nats.php for using Nats in php

## Installation
You can install the package via Composer:

```bash
composer require seddighi78/laravel-nats
```
The package will automatically register itself.

You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="Seddighi78\LaravelNats\NatsServiceProvider" 
```

## Connection
You should set the config env parameters to connect to your NATS server. for example :
```
NATS_HOST=localhost
NATS_PORT=4222
```
other configuration parameters can be found in `config/nats.php`.

## Usage
You need to run this command to subscribe to a subject and receive messages from the NATS server, it will dispatch a `MessageReceived.php` event when a new message is received and then you can listen to this event to do your job.
```bash
php artisan nats:subscriber:work {subject} {group?} {--connection=}
```

Arguments:
- `{subject}`: The name of your queue or stream (example: `main`)
- `{group?}`: (Optional) The consumer group name for the subscription. When specified, it enables NATS Queue Groups functionality.
- `{--connection}`: (Optional) The connection name to use.

### About NATS Queue Groups
When you specify a group name, the subscriber becomes part of a queue group in NATS. Queue groups allow you to distribute messages across multiple subscribers for load balancing. Here's how it works:

- Without a group: Each subscriber receives a copy of every message (pub/sub pattern)
- With a group: Messages are distributed across subscribers in the same group (queue pattern)

Example usage:
```bash
# Start subscriber in a queue group named 'workers'
php artisan nats:subscriber:work orders workers

# Start multiple instances to distribute the load
php artisan nats:subscriber:work orders workers # Instance 1
php artisan nats:subscriber:work orders workers # Instance 2
```

In the example above, if you have multiple subscribers with the same group name, only one subscriber in the group will receive each message, enabling load balancing across your subscribers.

## Example
These files are examples of using the package:

### Subscribe
Create a listener and listen to the `MessageReceived` event:
```php
namespace App\Listeners;

use Seddighi78\LaravelNats\Events\MessageReceived;

class PrintOnNatsMessageReceivedListener
{
    public function handle(MessageReceived $event): void
    {
        echo $event->message; // you can process the message here
    }
}
```

add the listener to `EventServiceProvider`
```php
use Seddighi78\LaravelNats\Events\MessageReceived;
use App\Listeners\PrintOnNatsMessageReceivedListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MessageReceived::class => [
            PrintOnNatsMessageReceivedListener::class,
        ]
    ];
}
```

### Publish
You can use this code to publish a message on a specific subject:
```php
$client = app(\Seddighi78\LaravelNats\Factories\NatsClientFactoryInterface::class)->getClient();
$client->publish('main', 'test');
```
When a message is published and the command `nats:subscriber:work` is running, the `MessageReceived` event will be dispatched, and you can listen to this event to do your job.

Also you can use the facade for calling these methods:
```php
use Seddighi78\LaravelNats\Facades\Nats;

// calling publish method 
Nats::getClient()->publish('main', 'test');
```
