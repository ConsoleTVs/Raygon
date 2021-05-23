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
     * @param string|object|array $events
     * @param string|callable|array $listeners
     * @return void
     */
    public function listen(string|object|array $events, string|callable|array $listeners): void;

    /**
     * Dispatches the current event.
     *
     * @param string|object $event
     * @param array $payload
     * @return array
     */
    public function dispatch(string|object $event, array $payload = []): array;

    /**
     * Determines if the given event has listeners.
     *
     * @param string|object $event
     * @return bool
     */
    public function hasListeners(string|object $event): bool;

    /**
     * Returns the current event listeners.
     *
     * @param string|object $event
     * @return array
     */
    public function listeners(string|object $event): array;
}
