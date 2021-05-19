<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Container;

use Erik\Raygon\Container\Binding;
use PHPUnit\Framework\TestCase;
use Erik\Raygon\Container\Container;
use Erik\Raygon\Contracts\Container\Container as ContainerContract;
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
    public function it_can_make_simple_classes_without_explicit_binding()
    {
        $container = new Container();

        $instance = $container->make(Sample::class);

        $this->assertTrue($instance instanceof Sample);
    }

    /** @test */
    public function it_can_pass_parameters_when_making()
    {
        $container = new Container();
        $container->bind(Sample::class, fn ($container, $parameters) => new Sample(
            name: $parameters['name'],
        ));
        $instance1 = $container->make(Sample::class, [
            'name' => 'Foo',
        ]);
        $instance2 = $container->make(Sample::class, [
            'name' => 'Bar',
        ]);

        $this->assertEquals($instance1->name, 'Foo');
        $this->assertEquals($instance2->name, 'Bar');
    }

    /** @test */
    public function it_can_bind_resolved_values()
    {
        $container = new Container();

        $instance1 = new Sample('Erik');

        $container->value(Sample::class, $instance1);
        $instance2 = $container->make(Sample::class);

        $this->assertEquals($instance1->name, 'Erik');
        $this->assertEquals($instance1->name, $instance2->name);
    }

    /** @test */
    public function it_implements_the_correct_contracts()
    {
        $this->assertArrayHasKey(ContainerContract::class, class_implements(Container::class));
    }
}
