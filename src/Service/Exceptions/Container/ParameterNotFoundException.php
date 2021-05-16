<?php

declare(strict_types=1);

namespace Erik\Raygon\Service\Exceptions\Container;

use Exception;

class ParameterNotFoundException extends Exception
{
    /**
     * Creates a new class instance.
     *
     * @param string $service
     */
    public function __construct(string $name)
    {
        $this->message = "The parameter `$name` was not found in the parameter list.";
    }
}
