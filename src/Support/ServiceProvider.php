<?php

declare(strict_types=1);

namespace Erik\Raygon\Support;

use Erik\Raygon\Contracts\Foundation\Application;

abstract class ServiceProvider
{
    /**
     * Stores the application that will
     * register the service.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Determines if the service provider
     * has been registered.
     *
     * @var bool
     */
    private bool $registered;

    /**
     * Determines if the service provider
     * has been booted.
     *
     * @var bool
     */
    private bool $booted;

    /**
     * Creates a new instance of the service provider.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Determines if the service provider
     * has been registered.
     *
     * @return bool
     */
    public function hasRegistered(): bool
    {
        return $this->registered;
    }

    /**
     * Determines if the service provider
     * has been booted.
     *
     * @return bool
     */
    public function hasBooted(): bool
    {
        return $this->booted;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    protected function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    protected function boot(): void
    {
        //
    }

    /**
     * Prepares the service provider by
     * registering it.
     *
     * @return void
     */
    public function prepare(): void
    {
        $this->register();

        $this->registered = true;
    }

    /**
     * Initializes the service provider
     * by booting it.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->boot();

        $this->booted = true;
    }
}
