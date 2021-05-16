<?php

declare(strict_types=1);

namespace Erik\Raygon\Exceptions\Container;

use Exception;

class ServiceNotFoundException extends Exception
{
    /**
     * Creates a new class instance.
     *
     * @param string $service
     */
    public function __construct(string $service)
    {
        $this->message = "The service `$service` was not found. Register it to the container first.";
    }
}
