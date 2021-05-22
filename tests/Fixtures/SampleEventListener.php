<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Fixtures;

use Erik\Raygon\Contracts\Log\Logger;

class SampleEventListener
{
    protected Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function handle(string $event): string
    {
        return "ok:$event";
    }
}
