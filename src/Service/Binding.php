<?php

declare(strict_types=1);

namespace Erik\Raygon\Service;

use Erik\Raygon\Service\Contracts\Binding as BindingContract;
use Erik\Raygon\Service\Contracts\Container;
use Erik\Raygon\Service\Exceptions\ContainerNotFoundException;

class Binding implements BindingContract
{
    /**
     * Stores the default container that will be used
     * to resolve the binding. You can still pass a container
     * to the resolve function if you don't have access to
     * the container when the binding is created.
     *
     * @var Container|null
     */
    protected ?Container $container;

    /**
     * Determines if the binding should
     * be a singleton or not.
     *
     * @var bool
     */
    protected bool $singleton = false;

    /**
     * Stores the binding resolver.
     *
     * @var callable
     */
    protected $resolver;

    /**
     * Creates a new instance of a binding.
     *
     * @param Container $container
     * @param bool $singleton
     * @param callable $resolver
     */
    public function __construct(callable $resolver, ?Container $container = null)
    {
        $this->resolver = $resolver;
        $this->container = $container;
    }

    /**
     * Resolves the current binding as a singleton.
     *
     * @return mixed
     */
    protected function resolveSingleton(): mixed
    {
        // Since `null` is a valid resolution value
        // we must have a flag to indicate if the resolution
        // has happened already at least once.
        static $hasBeenResolved = false;

        // The resolved value will be stored in this static variable
        // preserving state between calls.
        static $resolved;

        // Successive calls to this function when the binding
        // has already been resolved will return this variable
        // instead, acting as a singleton.
        if ($hasBeenResolved) {
            return $resolved;
        }

        $hasBeenResolved = true;
        return $resolved = ($this->resolver)($this->container);
    }

    /**
     * Set the current binding to be a singleton.
     *
     * @param bool $value
     * @return static
     */
    public function singleton(bool $value = true): static
    {
        $this->singleton = $value;

        return $this;
    }

    /**
     * Returns true if the binding is a singleton.
     *
     * @return bool
     */
    public function isSingleton(): bool
    {
        return $this->singleton;
    }

    /**
     * Sets the binding container instance.
     *
     * @param Container $container
     * @param bool $ignoreIfExists
     * @return static
     */
    public function container(?Container $container, bool $ignoreIfExists = false): static
    {
        if (is_null($this->container) || (!is_null($this->container) && !$ignoreIfExists)) {
            $this->container = $container;
        }

        return $this;
    }

    /**
     * Returns the currently used container if any.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * Resolves the current binding from
     * the container.
     *
     * @param Container|null $container
     * @return mixed
     * @throws ContainerNotFoundException
     */
    public function resolve(?Container $container = null): mixed
    {
        // The container that will be used might be null, thus allowing
        // to specify a the default container if it is.
        $container ??= $this->container;

        // In case there's no container to resolve the binding
        // an exception is thrown.
        if (is_null($container)) {
            throw new ContainerNotFoundException();
        }

        // The resolution logic might differ depending
        // on the singleton flag. If it is a singleton
        // we'll need to apply different logic to it.
        return ($this->isSingleton())
            ? $this->resolveSingleton()
            : ($this->resolver)($this->container);
    }
}
