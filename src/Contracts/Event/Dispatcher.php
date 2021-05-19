<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Event;

interface Dispatcher
{
    /**
     * Adds an event listener to the dispatcher.
     * Both parameters accept arrays to register
     * multiple listeners to multiple events if needed.
     *
     * @param string|Event|array $events
     * @param string|array $listeners
     * @return void
     */
    public function listen(string|Event|array $events, string|array $listeners): void;

    /**
     * Dispatches the current event.
     *
     * @param string|Event $event
     * @param array $payload
     * @return array
     */
    public function dispatch(string|Event $event, array $payload = []): array;

    /**
     * Determines if the given event has listeners.
     *
     * @param Event $event
     * @return bool
     */
    public function hasListeners(Event $event): bool;

    /**
     * Returns the current event listeners.
     *
     * @param Event $event
     * @return array
     */
    public function listeners(Event $event): array;
}
