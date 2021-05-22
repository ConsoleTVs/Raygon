<?php

declare(strict_types=1);

namespace Erik\Raygon\Log;

use Erik\Raygon\Support\ServiceProvider;
use Erik\Raygon\Contracts\Log\Logger as LoggerContract;
use Erik\Raygon\Log\Logger;

class LogServiceProvider extends ServiceProvider
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
            ->bind(LoggerContract::class, Logger::class)
            ->singleton();
    }
}
