<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Support;

use Stringable;

interface File extends Stringable
{
    /**
     * Returns the directory of the given file.
     *
     * @return Directory
     */
    public function directory(): Directory;

    /**
     * Returns the file name without the extension.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Returns the extension of the file.
     *
     * @return string
     */
    public function extension(): string;

    /**
     * Returns the file name with the extension.
     *
     * @return string
     */
    public function fullName(): string;

    /**
     * Returns the path of the given path.
     *
     * @return Path
     */
    public function path(): Path;
}
