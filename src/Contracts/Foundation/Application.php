<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Foundation;

interface Application
{
    /**
     * Returns the application version.
     *
     * @return string
     */
    public function version(): string;
}
