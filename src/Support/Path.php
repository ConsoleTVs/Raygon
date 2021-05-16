<?php

declare(strict_types=1);

namespace Erik\Raygon\Support;

use Erik\Raygon\Contracts\Support\Path as PathContract;
use Erik\Raygon\Exceptions\Support\InvalidPathException;

class Path implements PathContract
{
    /**
     * Stores the current path.
     *
     * @var string
     */
    protected string $path;

    /**
     * Creates a new instance of the class.
     *
     * @param string $path
     * @throws InvalidPathException
     */
    public function __construct(string $path)
    {
        $this->path = $path;

        if (count($this->segments()) === 1) {
            throw new InvalidPathException($this->path);
        }
    }

    /**
     * Returns the segments of the given path.
     *
     * @return array
     */
    public function segments(): array
    {
        return explode('/', $this->path);
    }

    /**
     * Returns the first segment found in the path.
     *
     * @return string
     */
    public function firstSegment(): string
    {
        $segments = $this->segments();

        return ($segments[0] === '')
            ? '/'
            : $segments[0];
    }

    /**
     * Returns the last segment found in the path.
     *
     * @return string
     */
    public function lastSegment(): string
    {
        $segments = $this->segments();

        return ($segments[count($segments) - 1] === '')
            ? '/'
            : $segments[count($segments) - 1];
    }

    /**
     * Returns a new Path as the result of
     * appending a path into the current one.
     *
     * @param PathContract $path
     * @return PathContract
     */
    // public function append(PathContract $path): PathContract
    // {
    //     //
    // }

    /**
     * Magic method {@see https://www.php.net/manual/en/language.oop5.magic.php}
     * called during serialization to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->path;
    }
}
