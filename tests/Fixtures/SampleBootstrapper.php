<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Fixtures;

use Erik\Raygon\Contracts\Foundation\Application;
use Erik\Raygon\Contracts\Foundation\Bootstrapper;

class SampleBootstrapper implements Bootstrapper
{
    /**
     * Handles the application bootstrap.
     *
     * @param Application $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        $app->value(Sample::class, new Sample('Erik'));
    }
}
