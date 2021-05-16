<?php

declare(strict_types=1);

namespace Erik\Raygon\Support;

use Erik\Raygon\Contracts\Support\Parameters as ParametersContract;
use Erik\Raygon\Exceptions\Support\ParameterNotFoundException;
use ReflectionClass;
use ReflectionParameter;
use ReflectionFunction;
use ReflectionMethod;

class Parameters implements ParametersContract
{
    /**
     * Stores the parameters.
     *
     * @var array
     */
    protected array $parameters;

    /**
     * Creates the parameters based on a function.
     *
     * @param callable|string $function
     * @return static
     */
    public static function function(callable|string $function): static
    {
        return new static(
            (new ReflectionFunction($function))->getParameters()
        );
    }

    /**
     * Creates the parameters based on a class constructor.
     *
     * @param string $class
     * @return static
     */
    public static function constructor(string $class): static
    {
        $constructor = (new ReflectionClass($class))->getConstructor();

        // There is the possibility that a class has no constructor.
        // If that's the case, the class does not need any parameters
        // thus, an empty Parameters instance can be returned.
        if (is_null($constructor)) {
            return new static();
        }

        // In case the constructor exists, we need to get its parameters
        // from the reflection class.
        return new static($constructor->getParameters());
    }

    /**
     * Creates the parameters bsaed on a class method.
     *
     * @param string|object $classOrInstance
     * @param string $method
     * @return static
     */
    public static function method(string|object $classOrInstance, string $method): static
    {
        return new static(
            (new ReflectionMethod($classOrInstance, $method))->getParameters(),
        );
    }

    /**
     * Creates a new instance of the class.
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Returns the parameter names of the parameter list.
     *
     * @return string[]
     */
    public function names(): array
    {
        return array_map(fn ($parameter) => $parameter->name, $this->parameters);
    }

    /**
     * Determines if the given parameter exists in
     * the parameter list.
     *
     * @param string $name
     * @return bool
     */
    public function hasParameter(string $name): bool
    {
        return in_array($name, $this->names());
    }

    /**
     * Returns the given parameter reflection.
     *
     * @param string $name
     * @return ReflectionParameter
     * @throws ParameterNotFoundException If the parameter is not found.
     */
    public function parameter(string $name): ReflectionParameter
    {
        // There is the possibility that the name of the parameter
        // is not found in the given parameter list. If that's the case
        // we'll throw an exception.
        if (!$this->hasParameter($name)) {
            throw new ParameterNotFoundException($name);
        }

        // current() will always return the fisrt element that matches the given
        // array filtering. We already made sure the parameter is always here,
        // so `false` will never be returned.
        return current(array_filter($this->parameters, fn ($parameter) => $parameter->name == $name));
    }

    /**
     * Returns the parameters with their respective types.
     *
     * @param bool $onlyTyped
     * @return array
     */
    public function types(bool $onlyTyped = false): array
    {
        $result = [];

        // Building the associative array consists of
        // iterating over the parameter names and assigning
        // their type as the value.
        foreach ($this->names() as $parameter) {
            if (is_null($type = $this->type($parameter)) && $onlyTyped) {
                continue;
            }

            $result[$parameter] = $type;
        }

        return $result;
    }

    /**
     * Returns the type of the given parameter name.
     *
     * @param string $name
     * @return string|null
     */
    public function type(string $name): ?string
    {
        // There is the possibility that the parameter type
        // has not been typehinted, therefore we should make sure
        // to understand that null is a possible response.
        return $this->parameter($name)->getType()?->getName();
    }

    /**
     * Returns the parameter names that are type hinted.
     *
     * @return array
     */
    public function typed(): array
    {
        return array_filter($this->names(), fn ($parameter) => !is_null($this->type($parameter)));
    }

    /**
     * Returns the parameter names that are not type hinted.
     *
     * @return array
     */
    public function untyped(): array
    {
        return array_filter($this->names(), fn ($parameter) => is_null($this->type($parameter)));
    }
}
