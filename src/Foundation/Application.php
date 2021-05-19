<?php

declare(strict_types=1);

namespace Erik\Raygon\Foundation;

use Erik\Raygon\Container\Container;
use Erik\Raygon\Contracts\Foundation\Application as ApplicationContract;
use Erik\Raygon\Support\Directory;
use Erik\Raygon\Support\ServiceProvider;

class Application extends Container implements ApplicationContract
{
    /**
     * Stores the application version.
     *
     * @var string
     */
    const VERSION = '0.1.0';

    /**
     * Determines if the application has been
     * booted already.
     *
     * @var bool
     */
    protected bool $booted = false;

    /**
     * Stores the base directory of the application.
     *
     * @var Directory
     */
    protected Directory $base;

    /**
     * Stores the list of registered service providers
     * in the application.
     *
     * @var ServiceProvider[]
     */
    protected array $providers = [];

    /**
     * Creates a new instance of the class.
     *
     * @param string $base
     */
    public function __construct(string $base)
    {
        parent::__construct();

        $this->base = new Directory($base);
        $this->value(ApplicationContract::class, $this);
    }

    /**
     * Returns the application version.
     *
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
    }

    /**
     * Determines if the application has
     * already been booted.
     *
     * @return bool
     */
    public function hasBooted(): bool
    {
        return $this->booted;
    }

    /**
     * Registers the given service provider into the application.
     *
     * @param string|ServiceProvider $provider
     * @param bool $force
     * @return ServiceProvider
     */
    public function register(string|ServiceProvider $provider, bool $force = false): ServiceProvider
    {
        // It is possible that the provider has already been added in
        // the application. If we don't want to force the registering, we may
        // already return it from the application providers.
        if ($this->hasProvider($provider) && !$force) {
            return $this->provider($provider);
        }

        // In case the provider is a string, we need to make sure
        // we create an instance of it first.
        if (is_string($provider)) {
            $provider = $this->call($provider);
        }

        // Prepare the given service provider.
        // This will call the provider's register method
        // where the developer is expected to place the
        // bindings nessesary or any registration logic.
        $provider->prepare();

        // In case the application has already been booted, we can
        // already initialize the provider as well, given that the other
        // services will already be registered. This will call the boot
        // method on the service provider.
        if ($this->hasBooted()) {
            $this->call([$provider, 'initialize']);
        }

        return $this->providers[] = $provider;
    }

    /**
     * Returns the service providers that match the given class or instance.
     * Returns all the providers if `$name` is null.
     *
     * @param string|ServiceProvider|null $name
     * @return array
     */
    public function providers(string|ServiceProvider|null $name = null): array
    {
        return (is_null($name))
            ? $this->providers
            : array_filter($this->providers, fn ($provider) => $provider instanceof $name);
    }

    /**
     * Determines if the provider exists in the application.
     *
     * @param string|ServiceProvider $provider
     * @return bool
     */
    public function hasProvider(string|ServiceProvider $name): bool
    {
        return count($this->providers($name)) > 0;
    }

    /**
     * Returns the provider of the application in case it exists. If it does
     * not exist, it will return null instead.
     *
     * @param string|ServiceProvider $name
     * @return ServiceProvider|null
     */
    public function provider(string|ServiceProvider $name): ?ServiceProvider
    {
        return (!$this->hasProvider($name))
            ? null
            : current($this->providers($name));
    }

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot(): void
    {
        // There's no need to boot the application
        // if it has already booted in the past.
        if ($this->hasBooted()) {
            return;
        }

        // Initialize all the application providers by
        // calling their respective `initialize` methods.
        // Using array_walk will allow further booting service
        // providers that are registered inside another provider's
        // boot method because array walk takes in a copy of it.
        array_walk(
            $this->providers,
            // We don't need to check if the provider has been
            // initialized already because the register method only
            // boots it if the application has been booted, and
            // this is happening right now...
            fn ($provider) => $this->call([$provider, 'initialize'])
        );

        $this->booted = true;
    }
}
