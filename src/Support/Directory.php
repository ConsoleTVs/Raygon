<?php

declare(strict_types=1);

namespace Erik\Raygon\Support;

use Erik\Raygon\Contracts\Support\Directory as DirectoryContract;
use Erik\Raygon\Contracts\Support\Path as PathContract;

class Directory implements DirectoryContract
{
    /**
     * Stores the path of the directory.
     *
     * @param PathContract $path
     */
    protected PathContract $path;

    /**
     * Creates a new class instance.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = new Path($path);
    }

    /**
     * Returns the directory name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->path->basename();
    }

    /**
     * Returns the path of the given path.
     *
     * @return PathContract
     */
    public function path(): PathContract
    {
        return $this->path;
    }

    /**
     * Returns a relative file on the given directory.
     *
     * @param string $relativeFile
     * @return File
     */
    // public function file(string $relativeFile): File;

    public function __toString(): string
    {
        return (string) $this->path;
    }
}
