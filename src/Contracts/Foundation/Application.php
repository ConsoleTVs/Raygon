<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Foundation;

use Erik\Raygon\Contracts\Container\Container;
use Erik\Raygon\Support\ServiceProvider;

interface Application extends Container
{
    /**
     * Returns the application version.
     *
     * @return string
     */
    public function version(): string;

    /**
     * Determines if the application has
     * already been booted.
     *
     * @return bool
     */
    public function hasBooted(): bool;

    /**
     * Registers the given service provider into the application.
     *
     * @param string|ServiceProvider $provider
     * @param bool $force
     * @return ServiceProvider
     */
    public function register(string|ServiceProvider $provider, bool $force = false): ServiceProvider;

    /**
     * Returns the service providers that match the given class or instance.
     * Returns all the providers if `$name` is null.
     *
     * @param string|ServiceProvider|null $name
     * @return array
     */
    public function providers(string|ServiceProvider|null $name = null): array;

    /**
     * Determines if the provider exists in the application.
     *
     * @param string|ServiceProvider $name
     * @return bool
     */
    public function hasProvider(string|ServiceProvider $name): bool;

    /**
     * Returns the provider of the application in case it exists. If it does
     * not exist, it will return null instead.
     *
     * @param string|ServiceProvider $name
     * @return ServiceProvider|null
     */
    public function provider(string|ServiceProvider $name): ?ServiceProvider;

    /**
     * Boot the application's service providers.
     *
     * @param array $bootstrappers
     * @return void
     */
    public function boot(array $bootstrappers = []): void;
}
