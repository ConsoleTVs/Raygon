<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Container;

use Erik\Raygon\Exceptions\Container\ServiceNotFoundException;
use Erik\Raygon\Exceptions\Container\ContainerNotFoundException;

interface Container
{
    /**
     * Creates a new globalized container instance
     * and returns it. Keep in mind this stores the
     * instance itself to the globalized container,
     * removing the previous one if any.
     *
     * @param mixed ...$parameters
     * @return static
     */
    public static function global(mixed ...$parameters): static;

    /**
     * Gets the current globalized instance.
     *
     * @return Container
     */
    public static function getGlobal(): ?Container;

    /**
     * Determines if the container has the given service
     * currently binded.
     *
     * @param string $service
     * @return bool
     */
    public function hasBinding(string $service): bool;

    /**
     * Returns the currently registered service binding.
     *
     * @param string $service
     * @return Binding
     * @throws ServiceNotFoundException If the service is not found.
     */
    public function binding(string $service): Binding;

    /**
     * Binds a service to the container.
     *
     * @param string $service
     * @param string|callable|null $resolver
     */
    public function bind(string $service, string|callable|null $resolver = null): Binding;

    /**
     * Registers an existing value as shared in the container.
     * This allows registering existing instances and computed values
     * as something that can be reused.
     *
     * @param string $service
     * @param mixed $value
     * @return Binding
     */
    public function value(string $service, mixed $value): Binding;

    /**
     * Makes an instance of the given service by resolving it.
     *
     * @param string $service
     * @param array $parameters
     * @param bool $forceContainer
     * @param bool $bindIfNotFound
     * @param bool $bindAsSingleton
     * @return mixed
     * @throws ServiceNotFoundException If the service isn't binded.
     * @throws ContainerNotFoundException Unless the binding has a default container or `$forceContainer` is true.
     */
    public function make(
        string $service,
        array $parameters = [],
        bool $forceContainer = false,
        bool $bindIfNotFound = true,
        bool $bindAsSingleton = false,
    ): mixed;

    /**
     * Calls the given function and automatically inject
     * the parameters from the container services.
     * Used for Dependency Injection (DI).
     *
     * @param callable|string|array $function
     * @param array $parameters
     * @return mixed
     */
    public function call(callable|string|array $callable, array $parameters = []): mixed;

    /**
     * Determines if the container is able to call the
     * given `$callable`.
     *
     * @param callable|string|array $callable
     * @return bool
     */
    public function canCall(callable|string|array $callable): bool;
}
