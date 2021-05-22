<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Container;

use Erik\Raygon\Foundation\Application;
use Erik\Raygon\Contracts\Event\Dispatcher as DispatcherContract;
use Erik\Raygon\Event\Dispatcher;
use Erik\Raygon\Tests\Fixtures\SampleEventListener;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    /** @test */
    public function it_can_dispatch_and_listen_events()
    {
        $app = new Application(__FILE__);
        $dispatcher = $app->call(Dispatcher::class);

        $dispatcher->listen('sample', SampleEventListener::class);

        $responses = $dispatcher->dispatch('sample');

        $this->assertCount(1, $responses);
        $this->assertEquals($responses[0], 'ok:sample');
    }

    /** @test */
    public function it_implements_the_correct_contracts()
    {
        $this->assertArrayHasKey(DispatcherContract::class, class_implements(Dispatcher::class));
    }
}
