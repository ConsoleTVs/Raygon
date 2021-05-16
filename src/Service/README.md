# Raygon Service

## Service Bindings

A binding is what is used to resolve services in Raygon.

To bind a new service to Raygon, simply bind it to the container using the `bind` function.

```php
$container = new Container();
// Simple binding (uses DI).
$container->bind(SampleService::class);
```

The binding will be resolved by instanciating the class and injecting any other missing dependencies
it needs to its constructor.

## Resolving bindings

```php
$container = new Container();
$container->bind(SampleService::class);
$service = $container->make(SampleService::class);
```

## Callable binding resolution

If you require additional parameters, or in short, more complex initialization logic, you can also pass in
a callable as a second parameter.

```php
$container = new Container();
// Normal initialization.
$container->bind(SampleService::class, fn () => new SampleService());
// DI initialization (Injects dependencies from the container).
$container->bind(SampleService::class, fn ($container) => $container->call(SampleService::class));
// DI with additional data.
$container->bind(SampleService::class, fn ($container) => $container->call(SampleService::class, [
    'name' => 'Èrik',
]));
```

## Interface binding

You can also bind interfaces to their respective implementations. To do such, simply bind the interface
as the first parameter, and the class as the second:

```php
$container = new Container();
// Normal interface binding (uses DI).
$container->bind(SampleContract::class, SampleService::class);
// Callable initialization (no DI).
$container->bind(SampleContract::class, fn () => new SampleService());
// Callable initialization (uses DI).
$container->bind(SampleContract::class, fn ($container) => $container->call(SampleService::class));
// Callable initialization with additional data:
$container->bind(SampleContract::class, fn ($container) => $container->call(SampleService::class, [
    'name' => 'Èrik',
]));
```

## Arbitrary key service

You can also bind arbitrary keys, meaning you're able to bind the following custom service:

```php
$container = new Container();
$container->bind('my-service', fn () => 'Hello, World');
```

## Singleton binding

You can also create a singleton binding by calling `singleton()` on the binding.
Singletons will only be resolved the first time they are required to be resolved.
Subsequent resolutions will return the same cached value from the binding.

```php
$container = new Container();
$container->bind(SampleService::class)->singleton();
$instance1 = $container->make(SampleService::class);
$instance2 = $container->make(SampleService::class);
// $instance1 === $instance2 // true
```

## Manual binding creation

If you want, you can also create the binding beforehard and bind it to a given container at a given moment.

```php
$container = new Container();
$binding = new Binding(fn () => 'Hello, World');
$container->bind('my-service', $binding);
```

## Changing resolution container

Bindings can also have their own container. By default, when you bind a service to the container,
the binding's container will be set to the current container unless the binding have a container already
assigned to it. To setup the container, you may pass it as the second argument of the binding constructor
or you can lazy-set it by calling the `container()` function.

Note: **I would not recommend doing this ever**

```php
$container = new Container();
$container2 = new Container();
// This binding will be resolved from $container2 instead of
$binding = new Binding(fn () => 'Hello', $container2);
$container->bind('my-service', $binding);
```

## Resolving a binding instance

Well, a binding can be resolved WITHOUT the container. You can call the `resolve` method on it to resolve it.

```php
$container = new Container();
$binding = new Binding(fn () => 'Hello', $container);
$binding->resolve();
```

Alternatively you can also create the binding without knowing the container and resolve it with it when
needed:

```php
$binding = new Binding(fn () => 'Hello');
// Later...
$container = new Container();
$binding->resolve($container);
```

## Dependency Injection

It is possible to automatically inject function parameters by type-hinting them
with a service key that has been binded to the container. Sadly, no arbitrary keys can
be used, and that leaves only class bindings.

The `call()` method on the container allows to call any function, constructor, method or static method in
your application and automatically inject the type-hinted parameters that are registered in the
container.

```php
class Sample {
    public function __construct(Math $math)
    {
        //
    }
}

$container = new Container();
$container->bind(Math::class);

$instance = $container->call(Sample::class);
```

This is what happens when we register a simple class binding under the hood. However, exposing
the call function allow for more complex situations to be handled. For instance:

```php
class Sample {
    public function random_number(Math $math): int
    {
        return $math->random();
    }
}

$container = new Container();
$container->bind(Math::class);
$instance = new Sample();
$result = $container->call([$instance, 'random_number']);
```

You can also call static methods on classes as such:

```php
class Sample {
    public static function random_number(Math $math): int
    {
        return $math->random();
    }
}

$container = new Container();
$container->bind(Math::class);
$instance = new Sample();
$result = $container->call([Sample::class, 'random_number']);
$result2 = $container->call('Sample::random_number'); // Not recommended.
```

You may also call simple functions that are defined in your codebase.

```php
public function example(Math $math): int
{
    return $math->random();
}

$container = new Container();
$container->bind(Math::class);
$result = $container->call('example');
```

## Dependency Injection with additional parameters

Ocasionally, your functions might need additional parameters. If that's the case,
you can pass them with an associative array as the second parameter of the `call()` method.

```php
class Sample {
    public function __construct(string $name, Math $math, int $multiplier)
    {
        //
    }
}

$container = new Container();
$container->bind(Math::class);

$instance = $container->call(Sample::class, [
    'name' => 'Èrik',
    'multiplier' => 2,
]);
```

The parameter order is not important as we're using an associative array.

Note: **It is not possible to perform dependency injection without parameter names as for now**.
