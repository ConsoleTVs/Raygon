<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Fixtures;

use Erik\Raygon\Support\Traits\Containable;

class DI
{
    use Containable;

    public Sample $sample;
    public ?string $a;
    public ?string $b;

    public function __construct(Sample $sample, ?string $a = null, ?string $b = null)
    {
        $this->sample = $sample;
        $this->a = $a;
        $this->b = $b;
    }
}
