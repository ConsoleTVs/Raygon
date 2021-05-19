<?php

declare(strict_types=1);

namespace Erik\Raygon\Support\Traits;

use Erik\Raygon\Contracts\Event\Dispatcher;
use Erik\Raygon\Exceptions\Container\ContainerNotFoundException;
use Erik\Raygon\Foundation\Application;

trait Dispatchable
{
    /**
     * Dispatches the given event to the application
     * event dispatcher.
     *
     * @param mixed ...$arguments
     * @return void
     */
    public static function dispatch(mixed ...$arguments)
    {
        $container = Application::getGlobal();

        if (is_null($container)) {
            throw new ContainerNotFoundException();
        }

        $dispatcher = $container->make(Dispatcher::class);

        return $dispatcher->dispatch(new static(...$arguments));
    }
}
