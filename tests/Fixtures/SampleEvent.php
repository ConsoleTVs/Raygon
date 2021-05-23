<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Fixtures;

class SampleEvent
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
