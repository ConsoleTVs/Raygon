<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Fixtures;

class Sample implements SampleContract
{
    public static $instances = 0;

    public static function resetInstanceCount(int $initial = 0): void
    {
        static::$instances = $initial;
    }

    public function __construct()
    {
        static::$instances++;
    }

    public function example(int $multiplier)
    {
        return 10 * $multiplier;
    }
}
