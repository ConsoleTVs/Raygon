<?php

declare(strict_types=1);

namespace Erik\Raygon\Log;

use Erik\Raygon\Contracts\Foundation\Application;
use Erik\Raygon\Contracts\Log\Logger as LoggerContract;

class Logger implements LoggerContract
{
    /**
     * Stores the application that will be used to log stuff.
     *
     * @var Application.
     */
    protected Application $app;

    /**
     * Creates a new instance of the class.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Logs the given message as an error.
     *
     * @param string $message
     * @return void
     */
    public function error(string $message): void
    {
        fprintf(STDERR, '[%s] %s', date("d-m-Y H:i:s"), $message);
    }

    /**
     * Logs the given message.
     *
     * @param string $message
     * @return void
     */
    public function log(string $message): void
    {
        fprintf(STDOUT, '[%s] %s', date("d-m-Y H:i:s"), $message);
    }
}
