<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Fixtures;

use Erik\Raygon\Support\Traits\Containable;

class Sample implements SampleContract
{
    use Containable;

    public static $instances = 0;
    public ?string $name;

    public static function resetInstanceCount(int $initial = 0): void
    {
        static::$instances = $initial;
    }

    public function __construct(?string $name = null)
    {
        $this->name = $name;
        static::$instances++;
    }

    public function example(int $multiplier)
    {
        return 10 * $multiplier;
    }
}
