<?php

declare(strict_types=1);

namespace Erik\Raygon\Exceptions\Support;

use Exception;

class PathNotFoundException extends Exception
{
    /**
     * Creates a new class instance.
     *
     * @param string $service
     */
    public function __construct(string $path)
    {
        $this->message = "The given path `$path` was not found. Make sure it's a real system path.";
    }
}
