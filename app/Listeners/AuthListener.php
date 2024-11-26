<?php

namespace App\Listeners;

use App\Events\RegisteredEvent;
use Illuminate\Events\Dispatcher;

class AuthListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handleUserRegistered(RegisteredEvent $event): void {}

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            RegisteredEvent::class => 'handleUserRegistered',

        ];
    }

    /**
     * Handle the event.
     */
}
