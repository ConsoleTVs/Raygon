<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Foundation;

use Erik\Raygon\Contracts\Foundation\Application;

interface Bootstrapper
{
    /**
     * Handles the application bootstrap.
     *
     * @param Application $app
     * @return void
     */
    public function bootstrap(Application $app): void;
}
