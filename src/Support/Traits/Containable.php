<?php

declare(strict_types=1);

namespace Erik\Raygon\Support\Traits;

use Erik\Raygon\Contracts\Container\Binding;
use Erik\Raygon\Contracts\Container\Container;
use Erik\Raygon\Exceptions\Container\ContainerNotFoundException;
use Erik\Raygon\Foundation\Application;

trait Containable
{
    /**
     * Stores the container where the class will
     * attempt to resolve from.
     *
     * @var Container|null
     */
    protected static ?Container $container = null;

    /**
     * Binds the service
     *
     * @param string|callable|null $resolver
     * @return Binding
     */
    public static function bind(string|callable|null $resolver = null): Binding
    {
        $container = Application::getGlobal();

        if (is_null($container)) {
            throw new ContainerNotFoundException();
        }

        return static::bindWith(static::$container, $resolver);
    }

    /**
     * Binds the given class into the container.
     *
     * @param Container $container
     * @param string|callable|null $resolver
     * @return Binding
     */
    public static function bindWith(Container $container, string|callable|null $resolver = null): Binding
    {
        return (static::$container = $container)->bind(static::class, $resolver);
    }

    /**
     * Creates a new instance of the given class.
     *
     * @return static
     */
    public static function make(): static
    {
        $container = static::$container ?? Application::getGlobal();

        if (is_null($container)) {
            throw new ContainerNotFoundException();
        }

        return static::makeWith($container);
    }

    /**
     * Creates a new instance of the given class.
     *
     * @param Container $container
     * @return static
     */
    public static function makeWith(Container $container): static
    {
        return $container->make(static::class);
    }
}
