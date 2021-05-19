<?php

declare(strict_types=1);

namespace Erik\Raygon\Container;

use Erik\Raygon\Support\Parameters;
use Erik\Raygon\Contracts\Container\Container as ContainerContract;
use Erik\Raygon\Contracts\Container\Binding as BindingContract;
use Erik\Raygon\Exceptions\Container\ServiceNotFoundException;
use ReflectionClass;

class Container implements ContainerContract
{
    /**
     * Stores a globalized container instance.
     *
     * This is the instance that's used to resolve certain
     * services that require a container instance and don't
     * have one at hand.
     */
    public static ?ContainerContract $global = null;

    /**
     * Stores the container bindings in an
     * associative array.
     *
     * The array keys are the service name.
     * The array values are the service binding.
     *
     * @var array
     */
    protected array $bindings = [];

    /**
     * Creates a new globalized container instance
     * and returns it. Keep in mind this stores the
     * instance itself to the globalized container,
     * removing the previous one if any.
     *
     * @param mixed ...$parameters
     * @return static
     */
    public static function global(mixed ...$parameters): static
    {
        return static::$global = new static(...$parameters);
    }

    /**
     * Gets the current globalized instance.
     *
     * @return ContainerContract
     */
    public static function getGlobal(): ?ContainerContract
    {
        return static::$global;
    }

    /**
     * Creates a new instance of the class.
     */
    public function __construct()
    {
        $this->value(ContainerContract::class, $this);
    }

    /**
     * Turns a resolver into a callable resolver.
     *
     * @param string $service
     * @param string|callable|null $resolver
     * @return callable
     */
    protected function callableResolver(string $service, string|callable|null $resolver): callable
    {
        // If the resolver is null we should attempt to create
        // a simple binding by just trying to instanciate the
        // service as a class.
        if (is_null($resolver) && class_exists($service)) {
            return fn (ContainerContract $container) => $container->call($service);
        }

        // If the resolver is a string it will be used to instantiate
        // that class instead but by using a simple binding again.
        if (is_string($resolver) && class_exists($resolver)) {
            return fn (ContainerContract $container) => $container->call($resolver);
        }

        // The resolver might actually be a callable already, making the
        // purpose of this function by default. In such cases, returning it
        // will already be enough.
        return $resolver;
    }

    /**
     * Creates a binding with the given service and resolver.
     *
     * @param string $service
     * @param string|callable|Binding|null $resolver
     * @return BindingContract
     */
    protected function createBinding(string $service, string|callable|Binding|null $resolver): BindingContract
    {
        return ($resolver instanceof Binding)
            ? $resolver->container($this, ignoreIfExists: true)
            : new Binding($this->callableResolver($service, $resolver), $this);
    }

    /**
     * Injects the current parameter types with the given
     * container resolution for that type.
     *
     * @param Parameters $parameters
     * @param array $otherParameters
     * @return array
     */
    protected function inject(Parameters $parameters, array $otherParameters): array
    {
        // We first try to inject the type-hinted types of the function.
        // If the type is not a registered binding, we simply null the value out.
        $parameters = array_map(
            fn ($type) => $this->hasBinding($type) ? $this->make($type) : null,
            $parameters->types(onlyTyped: true, onlyResolvableTypes: true),
        );

        // Since the function can be called with additional parameters we have to merge
        // them with out injected types. We also make them higher priority due user-defined
        // overwrites that must take precedence over the injected types.
        return array_merge($parameters, $otherParameters);
    }

    /**
     * Determines if the container has the given service
     * currently binded.
     *
     * @param string $service
     * @return bool
     */
    public function hasBinding(string $service): bool
    {
        return array_key_exists($service, $this->bindings);
    }

    /**
     * Returns the currently registered service binding.
     *
     * @param string $service
     * @return Binding
     * @throws ServiceNotFoundException If the service is not found.
     */
    public function binding(string $service): Binding
    {
        if (!$this->hasBinding($service)) {
            throw new ServiceNotFoundException($service);
        }

        return $this->bindings[$service];
    }

    /**
     * Binds a service to the container.
     *
     * @param string $service
     * @param string|callable|Binding|null $resolver
     */
    public function bind(string $service, string|callable|Binding|null $resolver = null): BindingContract
    {
        return $this->bindings[$service] = $this->createBinding($service, $resolver);
    }

    /**
     * Registers an existing value as shared in the container.
     * This allows registering existing instances and computed values
     * as something that can be reused.
     *
     * @param string $service
     * @param mixed $value
     * @return Binding
     */
    public function value(string $service, mixed $value): Binding
    {
        return $this->bindings[$service] = Binding::value($value);
    }

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
    ): mixed {
        // We might need to bind the service to the container in case it is not found.
        // This will create a simple binding that can be resolved instantly afterwards.
        if (!$this->hasBinding($service) && $bindIfNotFound) {
            $this->bind($service)->singleton($bindAsSingleton);
        }

        // The resolve container is already set when the binding is created.
        // However, if a custom binding instance is provided or the user has
        // changed the container to a different one then we can either force it
        // or passing null in order for it to use the currently setup one.
        return $this
            ->binding($service)
            ->resolve($forceContainer ? $this : null, $parameters);
    }

    /**
     * Calls the given function and automatically inject
     * the parameters from the container services.
     * Used for Dependency Injection (DI).
     *
     * @param callable|string|array $function
     * @param array $parameters
     * @return mixed
     */
    public function call(callable|string|array $callable, array $parameters = []): mixed
    {
        // Determines if the callable is a class, thus meaning that the
        // call will initialize a class instance and therefore, call a constructor.
        $isConstructor = is_string($callable) && class_exists($callable);

        // It is also possible that the callable is in form of a static string. eg 'Sample::abc'.
        // If that's the case we will need to convert it into an array callable as ['Sample', 'abc']
        // since the reflection is unable to understand the first case.
        $isStringStaticMethod = is_string($callable) && strpos($callable, '::') !== false;
        $explodedCallable = fn () => explode('::', (string) $callable);

        // A way for us to transform the parameters and inject their respective injected values
        // from the service container is to match against different type of `$callable` types.
        // in order to correctly get the reflected parameters from it.
        $parameters = match (true) {
            $isConstructor => $this->inject(Parameters::constructor((string) $callable), $parameters),
            $isStringStaticMethod => $this->inject(Parameters::method($explodedCallable()[0], $explodedCallable()[1]), $parameters),
            is_string($callable) => $this->inject(Parameters::function($callable), $parameters),
            is_array($callable) => $this->inject(Parameters::method($callable[0], $callable[1]), $parameters),
            default => $parameters,
        };

        // We still need to check if the call is a constructor because we can't
        // use call_user_func to initialize a class instance.
        return ($isConstructor)
            ? new $callable(...$parameters)
            : call_user_func($callable, ...$parameters);
    }

    /**
     * Determines if the container is able to call the
     * given `$callable`.
     *
     * @param callable|string|array $callable
     * @return bool
     */
    public function canCall(callable|string|array $callable): bool
    {
        // Determines if the callable is a class, thus meaning that the
        // call will initialize a class instance and therefore, call a constructor.
        $isConstructor = is_string($callable) && class_exists($callable);

        // It is also possible that the callable is in form of a static string. eg 'Sample::abc'.
        // If that's the case we will need to convert it into an array callable as ['Sample', 'abc']
        // since the reflection is unable to understand the first case.
        $isStringStaticMethod = is_string($callable) && strpos($callable, '::') !== false;
        $explodedCallable = fn () => explode('::', (string) $callable);

        return match (true) {
            $isConstructor => (new ReflectionClass($callable))->hasMethod('__construct'),
            $isStringStaticMethod => (new ReflectionClass($explodedCallable()[0]))->hasMethod($explodedCallable()[1]),
            is_string($callable) => function_exists($callable),
            is_array($callable) => (new ReflectionClass($callable[0]))->hasMethod($callable[1]),
            default => false,
        };
    }
}
