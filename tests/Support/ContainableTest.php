<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Service\Container;

use Erik\Raygon\Tests\Fixtures\DI;
use Erik\Raygon\Tests\Fixtures\Sample;
use PHPUnit\Framework\TestCase;
use Erik\Raygon\Support\Traits\Containable;
use Erik\Raygon\Container\Container;

class ContainableTest extends TestCase
{
    /** @test */
    public function it_can_use_the_traits()
    {
        $container = new Container();

        Sample::bindWith($container);
        DI::bindWith($container);

        $instance = DI::make();

        $this->assertTrue($instance instanceof DI);
        $this->assertTrue($instance->sample instanceof Sample);
        $this->assertEquals($instance->a, null);
        $this->assertEquals($instance->b, null);
    }

    /** @test */
    public function it_can_use_the_traits_2()
    {
        $container = new Container();

        Sample::bindWith($container);
        DI::bindWith($container, fn (Container $container) => $container->call(DI::class, [
            'a' => 'Hello',
            'b' => 'World',
        ]));

        $instance = DI::make();

        $this->assertTrue($instance instanceof DI);
        $this->assertTrue($instance->sample instanceof Sample);
        $this->assertEquals($instance->a, 'Hello');
        $this->assertEquals($instance->b, 'World');
    }

    /** @test */
    public function it_can_use_the_traits_as_singletons()
    {
        $container = new Container();

        Sample::bindWith($container);
        DI::bindWith($container)->singleton();

        $instance1 = DI::make();
        $instance2 = DI::make();

        $this->assertTrue($instance1 === $instance2);
    }

    /** @test */
    public function it_can_use_the_traits_as_singletons_2()
    {
        $container = new Container();

        Sample::bindWith($container)->singleton();
        DI::bindWith($container);

        $instance1 = DI::make();
        $instance2 = DI::make();

        $this->assertTrue($instance1 !== $instance2);
        $this->assertTrue($instance1->sample === $instance2->sample);
    }

    /** @test */
    public function it_have_the_traits()
    {
        $this->assertArrayHasKey(Containable::class, class_uses(Sample::class));
        $this->assertArrayHasKey(Containable::class, class_uses(DI::class));
    }
}
