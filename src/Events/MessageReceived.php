<?php

namespace Seddighi78\NatsLaravel\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MessageReceived
{
    use Dispatchable;

    public function __construct(
        public string $subject,
        public string $message
    )
    {
        //
    }
}