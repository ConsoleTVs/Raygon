<?php

declare(strict_types=1);

namespace Erik\Raygon\Event;

use Erik\Raygon\Contracts\Container\Container;
use Erik\Raygon\Contracts\Event\Dispatcher as DispatcherContract;

class Dispatcher implements DispatcherContract
{
    /**
     * Stores the application container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Stores the event listeners.
     *
     * @var array
     */
    protected array $listeners = [];

    /**
     * Creates a new instance of the class.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Returns the key of a given event.
     *
     * @param string|object $event
     * @return string
     */
    protected function key(string|object $event): string
    {
        return (is_object($event))
            ? $event::class
            : $event;
    }

    /**
     * Adds an event listener to the dispatcher.
     * Both parameters accept arrays to register
     * multiple listeners to multiple events if needed.
     *
     * @param string|object|array $events
     * @param string|callable|array $listeners
     * @return void
     */
    public function listen(string|object|array $events, string|callable|array $listeners): void
    {
        // Valid Examples:
        // - Event::class
        // - new Event()
        // - 'event'
        // - [...]

        // There is the possibility that we're attempting to add
        // multiple listeners to multiple events.
        foreach (is_array($events) ? $events : [$events] as $event) {
            // To setup the listener, we first need to make
            // sure we got the right event key.
            $key = $this->key($event);

            // Create the event listeners in case they are classes.
            $listeners = array_map(
                fn ($listener) => is_string($listener) && class_exists($listener)
                    ? $this->container->make($listener)
                    : $listener,
                (array) $listeners
            );

            // To register the listener we simply need to add
            // it to our listeners array.
            $this->listeners[$key] = array_merge($this->listeners[$key] ?? [], $listeners);
        }
    }

    /**
     * Dispatches the current event.
     *
     * @param string|object $event
     * @param array $payload
     * @return array
     */
    public function dispatch(string|object $event, array $payload = []): array
    {
        // We must make the event in case it is a string and a class.
        // This will ensure we now always get an EventContract class.
        if (is_string($event) && class_exists($event)) {
            $event = $this->container->make($event);
        }

        // Responses array will be used as the result
        // of the function, it will store the results
        // of the event listeners's `handle` method.
        $responses = [];

        // We iterate over the current listeners of the event and call
        // the `handle` method on them. We also store the result of that
        // method in the responses array that is futher returned.
        foreach ($this->listeners[$this->key($event)] as $listener) {
            $responses[] = match (true) {
                is_callable($listener) => $listener($event, $payload),
                is_object($listener) && method_exists($listener, 'handle') => $listener->handle($event, $payload),
            };
        }

        return $responses;
    }

    /**
     * Determines if the given event has listeners.
     *
     * @param string|object $event
     * @return bool
     */
    public function hasListeners(string|object $event): bool
    {
        return array_key_exists($this->key($event), $this->listeners);
    }

    /**
     * Returns the current event listeners.
     *
     * @param string|object $event
     * @return array
     */
    public function listeners(string|object $event): array
    {
        return ($this->hasListeners($event))
            ? $this->listeners[$this->key($event)]
            : [];
    }
}
