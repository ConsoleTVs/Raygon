<?php

declare(strict_types=1);

namespace Erik\Raygon\Container;

use Erik\Raygon\Contracts\Container\Binding as BindingContract;
use Erik\Raygon\Contracts\Container\Container;
use Erik\Raygon\Exceptions\Container\ContainerNotFoundException;
use Erik\Raygon\Exceptions\Container\ResolverNotFoundException;

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
     * @var callable|null
     */
    protected $resolver;

    /**
     * Determines if the variable has been resolved
     * at least once.
     *
     * @var bool
     */
    protected bool $hasBeenResolved = false;

    /**
     * Stores the last resolved value of the binding.
     *
     * @var mixed
     */
    protected mixed $lastResolvedValue = null;

    /**
     * Creates a binding from a given value.
     * The bindings acts as a resolved singleton.
     *
     * @param mixed $value
     * @return static
     */
    public static function value(mixed $value): static
    {
        return (new static())
            ->singleton()
            ->resolved($value);
    }

    /**
     * Creates a new instance of a binding.
     *
     * @param Container $container
     * @param bool $singleton
     * @param callable $resolver
     */
    public function __construct(?callable $resolver = null, ?Container $container = null)
    {
        $this->resolver = $resolver;
        $this->container = $container;
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
     * @param array $parameters
     * @return mixed
     * @throws ContainerNotFoundException
     * @throws ResolverNotFoundException
     */
    public function resolve(?Container $container = null, array $parameters = []): mixed
    {
        // The resolution logic might differ depending
        // on the singleton flag.
        if ($this->isSingleton() && $this->hasBeenResolved) {
            return $this->lastResolvedValue;
        }

        // The container that will be used might be null, thus allowing
        // to specify a the default container if it is.
        $container ??= $this->container;

        // In case there's no container to resolve the binding
        // an exception is thrown.
        if (is_null($container)) {
            throw new ContainerNotFoundException();
        }

        // Check if the resolved exists before attempting to
        // resolve the value of the binding.
        if (is_null($this->resolver)) {
            throw new ResolverNotFoundException();
        }

        // Resolve the binding value and set the binding
        // to resolved (at least once).
        $value = ($this->resolver)($this->container, $parameters);
        $this->resolved($value);

        return $value;
    }

    /**
     * Resolves the binding with the given value.
     *
     * @param mixed $value
     * @return static
     */
    public function resolved(mixed $value): static
    {
        // Marking the binding as resolved will allow singletons
        // to return values directly without having to resolve them
        // again when called.
        $this->hasBeenResolved = true;

        // We need to store the last resolved value for storage purposes.
        // This is the value that singletons will return afterwards.
        $this->lastResolvedValue = $value;

        return $this;
    }
}
