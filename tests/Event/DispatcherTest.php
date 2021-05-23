<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Container;

use Erik\Raygon\Foundation\Application;
use Erik\Raygon\Contracts\Event\Dispatcher as DispatcherContract;
use Erik\Raygon\Event\Dispatcher;
use Erik\Raygon\Tests\Fixtures\SampleEvent;
use Erik\Raygon\Tests\Fixtures\SampleEventListener;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    /** @test */
    public function it_can_add_event_listeners()
    {
        $app = new Application(__FILE__);
        $dispatcher = $app->make(Dispatcher::class);

        $dispatcher->listen('sample', SampleEventListener::class);

        $this->assertCount(1, $dispatcher->listeners('sample'));
        $this->assertEquals($dispatcher->hasListeners('sample'), true);
    }

    /** @test */
    public function it_can_dispatch_and_listen_events()
    {
        $app = new Application(__FILE__);
        $dispatcher = $app->make(Dispatcher::class);

        $dispatcher->listen('sample', SampleEventListener::class);
        $responses = $dispatcher->dispatch('sample');

        $this->assertCount(1, $responses);
        $this->assertEquals($responses[0], 'ok:sample');
    }

    /** @test */
    public function it_can_dispatch_and_listen_callable_events()
    {
        $app = new Application(__FILE__);
        $dispatcher = $app->make(Dispatcher::class);

        $dispatcher->listen('sample', fn ($event) => "ok:$event");
        $responses = $dispatcher->dispatch('sample');

        $this->assertCount(1, $responses);
        $this->assertEquals($responses[0], 'ok:sample');
    }

    /** @test */
    public function it_can_dispatch_and_listen_events_with_payload()
    {
        $app = new Application(__FILE__);
        $dispatcher = $app->make(Dispatcher::class);

        $dispatcher->listen('sample', fn ($event, $payload) => "ok:$event:$payload[0]");
        $responses = $dispatcher->dispatch('sample', [10]);

        $this->assertCount(1, $responses);
        $this->assertEquals($responses[0], 'ok:sample:10');
    }

    /** @test */
    public function it_can_dispatch_and_listen_events_with_payload_on_event()
    {
        $app = new Application(__FILE__);
        $dispatcher = $app->make(Dispatcher::class);

        $dispatcher->listen(SampleEvent::class, fn ($event) => "ok:$event->name");
        $responses = $dispatcher->dispatch(new SampleEvent('erik'));

        $this->assertCount(1, $responses);
        $this->assertEquals($responses[0], 'ok:erik');
    }

    /** @test */
    public function it_can_dispatch_and_listen_events_with_payload_on_event_2()
    {
        $app = new Application(__FILE__);
        $dispatcher = $app->make(Dispatcher::class);

        $event = new SampleEvent('erik');
        $dispatcher->listen($event, fn ($event) => "ok:$event->name");
        $responses = $dispatcher->dispatch($event);

        $this->assertCount(1, $responses);
        $this->assertEquals($responses[0], 'ok:erik');
    }

    /** @test */
    public function it_implements_the_correct_contracts()
    {
        $this->assertArrayHasKey(DispatcherContract::class, class_implements(Dispatcher::class));
    }
}
