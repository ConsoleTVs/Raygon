<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Support;

use ReflectionParameter;

interface Parameters
{
    /**
     * Creates the parameters based on a function.
     *
     * @param callable|string $function
     * @return static
     */
    public static function function(callable|string $function): static;

    /**
     * Creates the parameters based on a class constructor.
     *
     * @param string $class
     * @return static
     */
    public static function constructor(string $class): static;

    /**
     * Creates the parameters bsaed on a class method.
     *
     * @param string|object $classOrInstance
     * @param string $method
     * @return static
     */
    public static function method(string|object $classOrInstance, string $method): static;

    /**
     * Returns the parameter names of the parameter list.
     *
     * @return string[]
     */
    public function names(): array;

    /**
     * Determines if the given parameter exists in
     * the parameter list.
     *
     * @param string $name
     * @return bool
     */
    public function hasParameter(string $name): bool;

    /**
     * Returns the given parameter reflection.
     *
     * @param string $name
     * @return ReflectionParameter|null
     */
    public function parameter(string $name): ?ReflectionParameter;

    /**
     * Returns the parameters with their respective types.
     *
     * @param bool $onlyTyped
     * @return array
     */
    public function types(bool $onlyTyped = false): array;

    /**
     * Returns the type of the given parameter name.
     *
     * @param string $name
     * @return string|null
     */
    public function type(string $name): ?string;

    /**
     * Returns the parameter names that are type hinted.
     *
     * @return array
     */
    public function typed(): array;

    /**
     * Returns the parameter names that are not type hinted.
     *
     * @return array
     */
    public function untyped(): array;
}
