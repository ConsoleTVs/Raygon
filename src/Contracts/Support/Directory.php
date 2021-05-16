<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Support;

use Stringable;

interface Directory extends Stringable
{
    /**
     * Returns the directory name.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Returns the path of the given path.
     *
     * @return Path
     */
    public function path(): Path;

    /**
     * Returns the parent directory relative
     * to the current one.
     *
     * @return Directory
     */
    // public function parent(): Directory;

    /**
     * Returns a relative file on the given directory.
     *
     * @param string $relativeFile
     * @return File
     */
    // public function file(string $relativeFile): File;
}
