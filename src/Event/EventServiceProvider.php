<?php

declare(strict_types=1);

namespace Erik\Raygon\Event;

use Erik\Raygon\Event\Dispatcher;
use Erik\Raygon\Contracts\Event\Dispatcher as DispatcherContract;
use Erik\Raygon\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this
            ->app
            ->bind(DispatcherContract::class, Dispatcher::class)
            ->singleton();
    }
}
