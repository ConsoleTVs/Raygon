<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Container;

use Erik\Raygon\Exceptions\Container\ResolverNotFoundException;
use Erik\Raygon\Exceptions\Container\ContainerNotFoundException;

interface Binding
{
    /**
     * Creates a binding from a given value.
     * The bindings acts as a resolved singleton.
     *
     * @param mixed $value
     * @return static
     */
    public static function value(mixed $value): static;

    /**
     * Set the current binding to be a singleton.
     *
     * @param bool $value
     * @return static
     */
    public function singleton(bool $value = true): static;

    /**
     * Returns true if the binding is a singleton.
     *
     * @return bool
     */
    public function isSingleton(): bool;

    /**
     * Sets the binding container instance.
     *
     * @param Container $container
     * @param bool $ignoreIfExists
     * @return static
     */
    public function container(?Container $container, bool $ignoreIfExists = false): static;

    /**
     * Returns the currently used container if any.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

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
    public function resolve(?Container $container = null, array $parameters = []): mixed;

    /**
     * Resolves the binding with the given value.
     *
     * @param mixed $value
     * @return static
     */
    public function resolved(mixed $value): static;
}
