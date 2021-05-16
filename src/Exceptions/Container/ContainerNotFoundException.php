<?php

declare(strict_types=1);

namespace Erik\Raygon\Exceptions\Container;

use Exception;

class ContainerNotFoundException extends Exception
{
    /**
     * Creates a new class instance.
     *
     * @param string $service
     */
    public function __construct()
    {
        $this->message = "The container was not found. Add a container first.";
    }
}
