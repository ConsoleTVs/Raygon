<?php

declare(strict_types=1);

namespace Erik\Raygon\Env;

use Erik\Raygon\Contracts\Env\Repository as EnvRepository;
use Erik\Raygon\Contracts\Foundation\Application;

class Repository implements EnvRepository
{
    /**
     * Stores the application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Stores the variables of the env file.
     *
     * @var array
     */
    protected array $variables;

    /**
     * Creates a new class instance.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->reload();
    }

    /**
     * Gets the given key from the environment
     * without castings nor expansions.
     *
     * @param string $key
     * @return string
     */
    public function getRaw(string $key): ?string
    {
        return ($this->has($key))
            ? $this->variables[$key]
            : null;
    }

    /**
     * Gets the given key from the environment.
     *
     * @param string $key
     * @return string|bool|int|float|null
     */
    public function get(string $key): string|bool|int|float|null
    {
        //
    }

    /**
     * Returns true if the key is in the repository.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->variables);
    }

    /**
     * Reloads the environment variables.
     *
     * @return void
     */
    public function reload(): void
    {
        $file = $this->app;
    }
}
