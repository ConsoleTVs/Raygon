<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Env;

interface Repository
{
    /**
     * Gets the given key from the environment
     * without castings nor expansions.
     *
     * @param string $key
     * @return string|null
     */
    public function getRaw(string $key): ?string;

    /**
     * Gets the given key from the environment.
     *
     * @param string $key
     * @return string|bool|int|float|null
     */
    public function get(string $key): string|bool|int|float|null;

    /**
     * Returns true if the key is in the repository.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Reloads the environment variables.
     *
     * @return void
     */
    public function reload(): void;
}
