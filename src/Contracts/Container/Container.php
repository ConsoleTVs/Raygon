<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Container;

use Erik\Raygon\Exceptions\Container\ServiceNotFoundException;
use Erik\Raygon\Exceptions\Container\ContainerNotFoundException;

interface Container
{
    /**
     * Creates a new globalized container.
     *
     * @return Container
     */
    public static function global(): Container;

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
     * @param bool $forceContainer
     * @return mixed
     * @throws ServiceNotFoundException If the service isn't binded.
     * @throws ContainerNotFoundException Unless the binding has a default container or `$forceContainer` is true.
     */
    public function make(string $service, bool $forceContainer = false): mixed;

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
}
