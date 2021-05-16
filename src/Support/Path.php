<?php

declare(strict_types=1);

namespace Erik\Raygon\Support;

use Erik\Raygon\Contracts\Support\Path as PathContract;
use Erik\Raygon\Contracts\Support\File as FileContract;
use Erik\Raygon\Contracts\Support\Directory as DirectoryContract;
use Erik\Raygon\Exceptions\Support\PathNotFoundException;

class Path implements PathContract
{
    /**
     * Stores the current path.
     *
     * @var string
     */
    protected string $path;

    /**
     * Determines if the path is a real system
     * path, and therefore, system functions can
     * be used to resolve symlinks, relative access
     * or determining if files and directories really exist.
     *
     * @var bool
     */
    protected bool $real;

    /**
     * Creates a new instance of the class.
     *
     * @param string $path
     * @throws PathNotFoundException If the path is not real.
     */
    public function __construct(string $path)
    {
        $realPath = realpath($path);

        if ($realPath === false) {
            throw new PathNotFoundException($path);
        }

        $this->path = $realPath;
    }

    /**
     * Returns the last segment found in the path.
     *
     * @return string
     */
    public function basename(): string
    {
        return basename($this->path);
    }

    /**
     * Determines if the path is a file.
     *
     * @return bool
     */
    public function isFile(): bool
    {
        return is_file($this->path);
    }

    /**
     * Returns the file path.
     *
     * @return FileContract|null
     */
    public function file(): ?FileContract
    {
        if (!$this->isFile()) {
            return null;
        }

        return new File($this->path);
    }

    /**
     * Determines if the path is a directory.
     *
     * @return bool
     */
    public function isDirectory(): bool
    {
        return is_dir($this->path);
    }

    /**
     * Returns the directory of the path.
     *
     * If the path is a file, the folder location
     * of that file is returned instead.
     *
     * @return DirectoryContract
     */
    public function directory(): DirectoryContract
    {
        return new Directory(
            $this->isDirectory() ? $this->path : dirname($this->path),
        );
    }

    /**
     * Returns the information about the path.
     *
     * @param int $flags
     * @return array|string
     */
    public function info(int $flags = PATHINFO_ALL): array|string
    {
        return pathinfo($this->path);
    }

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
