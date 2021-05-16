<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Service;

use Erik\Raygon\Service\Binding;
use PHPUnit\Framework\TestCase;
use Erik\Raygon\Service\Container;
use Erik\Raygon\Service\Contracts\Container as ContainerContract;
use Erik\Raygon\Tests\Fixtures\DI;
use Erik\Raygon\Tests\Fixtures\Sample;
use Erik\Raygon\Tests\Fixtures\SampleContract;

class ContainerTest extends TestCase
{
    /** @test */
    public function it_can_resolve_binded_services()
    {
        $container = new Container();

        $container->bind('sample', fn () => 10);
        $container->bind(Sample::class);
        $container->bind(SampleContract::class, Sample::class);
        $container->bind('sample2', fn ($container) => $container->make('sample'));
        $binding1 = $container->bind('sample3', new Binding(fn () => 10));
        $binding2 = $container->bind('sample4', new Binding(fn () => 10, $container));

        $this->assertEquals($binding1->getContainer(), $binding2->getContainer());

        $this->assertEquals($container->make('sample'), 10);
        $this->assertEquals($container->make(Sample::class)::class, Sample::class);
        $this->assertEquals($container->make(SampleContract::class)::class, Sample::class);
        $this->assertEquals($container->make('sample2'), 10);
        $this->assertEquals($container->make('sample3'), 10);
        $this->assertEquals($container->make('sample4'), 10);
    }

    /** @test */
    public function it_resolves_singletons_once()
    {
        Sample::resetInstanceCount();

        $container = new Container();

        $container->bind(Sample::class)->singleton();

        $container->make(Sample::class);
        $container->make(Sample::class);
        $container->make(Sample::class);

        $this->assertEquals(Sample::$instances, 1);
    }

    /** @test */
    public function it_can_do_dependency_injection()
    {
        $container = new Container();

        $container->bind(Sample::class);

        $instance = $container->call(DI::class, ['a' => 'Hello', 'b' => 'World']);

        $this->assertTrue($instance instanceof DI);
        $this->assertTrue($instance->sample instanceof Sample);
        $this->assertEquals($instance->a, 'Hello');
        $this->assertEquals($instance->b, 'World');
    }

    /** @test */
    public function it_automatically_injects_dependencies_of_binded_classes()
    {
        $container = new Container();

        $container->bind(Sample::class);
        $container->bind(DI::class);

        $instance = $container->make(DI::class);

        $this->assertTrue($instance instanceof DI);
        $this->assertTrue($instance->sample instanceof Sample);
        $this->assertEquals($instance->a, null);
        $this->assertEquals($instance->b, null);
    }

    /** @test */
    public function it_can_manually_resolve_by_calling()
    {
        $container = new Container();

        $container->bind(Sample::class);
        $container->bind(DI::class, fn (ContainerContract $container) => $container->call(DI::class, [
            'a' => 'Hello',
            'b' => 'World',
        ]));

        $instance = $container->make(DI::class);

        $this->assertTrue($instance instanceof DI);
        $this->assertTrue($instance->sample instanceof Sample);
        $this->assertEquals($instance->a, 'Hello');
        $this->assertEquals($instance->b, 'World');
    }

    /** @test */
    public function it_implements_the_correct_contracts()
    {
        $this->assertArrayHasKey(ContainerContract::class, class_implements(Container::class));
    }
}
