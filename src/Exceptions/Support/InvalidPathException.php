<?php

declare(strict_types=1);

namespace Erik\Raygon\Exceptions\Support;

use Exception;

class InvalidPathException extends Exception
{
    /**
     * Creates a new class instance.
     *
     * @param string $service
     */
    public function __construct(string $path)
    {
        $this->message = "The path `$path` is invalid, please specify a correct path.";
    }
}
