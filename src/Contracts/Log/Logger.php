<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Log;

interface Logger
{
    /**
     * Logs the given message as an error.
     *
     * @param string $message
     * @return void
     */
    public function error(string $message): void;

    /**
     * Logs the given message.
     *
     * @param string $message
     * @return void
     */
    public function log(string $message): void;
}
