<?php

declare(strict_types=1);

namespace Erik\Raygon\Foundation;

use Erik\Raygon\Container\Container;
use Erik\Raygon\Contracts\Foundation\Application as ApplicationContract;
use Erik\Raygon\Support\Directory;

class Application extends Container implements ApplicationContract
{
    /**
     * Stores the application version.
     *
     * @var string
     */
    const VERSION = '0.1.0';

    /**
     * Stores the base directory of the application.
     *
     * @var Directory
     */
    protected Directory $base;

    /**
     * Creates a new instance of the class.
     *
     * @param string $base
     */
    public function __construct(string $base)
    {
        parent::__construct();

        $this->base = new Directory($base);
        $this->value(ApplicationContract::class, $this);
    }

    /**
     * Returns the application version.
     *
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
    }
}
