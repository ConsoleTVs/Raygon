<?php

declare(strict_types=1);

namespace Erik\Raygon\Contracts\Support;

use Stringable;

interface Path extends Stringable
{
    /**
     * Returns the segments of the given path.
     *
     * @return array
     */
    public function segments(): array;

    /**
     * Returns the first segment found in the path.
     *
     * @return string
     */
    public function firstSegment(): string;

    /**
     * Returns the last segment found in the path.
     *
     * @return string
     */
    public function lastSegment(): string;

    /**
     * Returns a new Path as the result of
     * appending a path into the current one.
     *
     * @param Path $path
     * @return Path
     */
    // public function append(Path $path): Path;
}
