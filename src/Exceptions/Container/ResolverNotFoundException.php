<?php

declare(strict_types=1);

namespace Erik\Raygon\Exceptions\Container;

use Exception;

class ResolverNotFoundException extends Exception
{
    /**
     * Creates a new class instance.
     *
     * @param string $service
     */
    public function __construct()
    {
        $this->message = "The resolved was not found. Add a resolver first.";
    }
}
