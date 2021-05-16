<?php

declare(strict_types=1);

namespace Erik\Raygon\Support;

use Erik\Raygon\Contracts\Support\File as FileContract;
use Erik\Raygon\Contracts\Support\Path as PathContract;

class File implements FileContract
{
    /**
     * Stores the path of the file.
     *
     * @var Path
     */
    protected Path $path;

    /**
     * Creates a new instance of the class.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = new Path($path);
    }

    /**
     * Returns the directory of the given file.
     *
     * @return Directory
     */
    public function directory(): Directory
    {
        return $this->path->directory();
    }

    /**
     * Returns the file name without the extension.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->path->info(PATHINFO_FILENAME);
    }

    /**
     * Returns the extension of the file.
     *
     * @return string
     */
    public function extension(): string
    {
        return $this->path->info(PATHINFO_EXTENSION);
    }

    /**
     * Returns the file name with the extension.
     *
     * @return string
     */
    public function fullName(): string
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
     * Magic method {@see https://www.php.net/manual/en/language.oop5.magic.php}
     * called during serialization to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->path;
    }
}
