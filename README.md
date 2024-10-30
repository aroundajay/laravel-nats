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
php artisan nats:subscriber:work {subject}
```
the `{subject}` is the name of your queue or stream example: `main`

also, you can set the connection name using `--connection` option.

## Example
these files are examples of using
### Subscribe
create a listener and listen to the `MessageReceived` event
```php
namespace App\Listeners;

use Seddighi78\LaravelNats\Events\MessageReceived;

class PrintOnNatsMessageReceivedListener
{
    public function handle(MessageReceived $event): void
    {
        echo $event->message; // you can proccess the messeage here
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
you can use this code to publish a message on a specific subject
```php
$client = app(\Seddighi78\LaravelNats\Factories\NatsClientFactoryInterface::class)->getClient();
$client->publish('main', 'test');
```
When a message is published and the command `nats:subscriber:work` is running, the `MessageReceived` event will be dispatched, and you can listen to this event to do your job.
