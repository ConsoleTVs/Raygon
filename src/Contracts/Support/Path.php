<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Support;

use Stringable;

interface Path extends Stringable
{
    /**
     * Returns trailing name component of path.
     *
     * @return string
     */
    public function basename(): string;

    /**
     * Determines if the path is a file.
     *
     * @return bool
     */
    public function isFile(): bool;

    /**
     * Returns the file path.
     *
     * @return File|null
     */
    public function file(): ?File;

    /**
     * Determines if the path is a directory.
     *
     * @return bool
     */
    public function isDirectory(): bool;

    /**
     * Returns the directory of the path.
     *
     * If the path is a file, the folder location
     * of that file is returned instead.
     *
     * @return Directory
     */
    public function directory(): Directory;

    /**
     * Returns the information about the path.
     *
     * @param int $flags
     * @return array|string
     */
    public function info(int $flags = PATHINFO_ALL): array|string;
}
