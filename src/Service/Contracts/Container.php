<?php

declare(strict_types=1);

namespace Erik\Raygon\Service\Contracts;

use Erik\Raygon\Service\Exceptions\ServiceNotFoundException;
use Erik\Raygon\Service\Exceptions\ContainerNotFoundException;

interface Container
{
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
     * Makes an instance of the given service by resolving it.
     *
     * @param string $service
     * @return mixed
     * @throws ServiceNotFoundException If the service isn't binded.
     * @throws ContainerNotFoundException Unless the binding has a default container or `$forceContainer` is true.
     */
    public function make(string $service): mixed;

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
