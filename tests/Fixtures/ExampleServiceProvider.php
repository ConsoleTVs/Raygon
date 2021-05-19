<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Fixtures;

use Erik\Raygon\Support\ServiceProvider;

class ExampleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    protected function register(): void
    {
        $this->app->bind(Sample::class, fn () => new Sample('Erik'));
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
}
