<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Service\Container;

use Erik\Raygon\Support\Parameters;
use Erik\Raygon\Contracts\Support\Parameters as ParametersContract;
use Erik\Raygon\Tests\Fixtures\DI;
use Erik\Raygon\Tests\Fixtures\Sample;
use PHPUnit\Framework\TestCase;

class ParametersTest extends TestCase
{
    /** @test */
    public function it_can_get_parameters_from_constructors()
    {
        $parameters = Parameters::constructor(DI::class);

        $this->assertCount(3, $parameters->names());
        $this->assertTrue($parameters->hasParameter('sample'));
        $this->assertTrue($parameters->hasParameter('a'));
        $this->assertTrue($parameters->hasParameter('b'));
        $this->assertEquals($parameters->type('sample'), Sample::class);
    }

    /** @test */
    public function it_can_get_parameters_from_functions()
    {
        $sample = fn (int $multiplier) => 10 * $multiplier;

        $parameters = Parameters::function($sample);

        $this->assertCount(1, $parameters->names());
        $this->assertTrue($parameters->hasParameter('multiplier'));
        $this->assertEquals($parameters->type('multiplier'), 'int');
    }

    /** @test */
    public function it_can_get_parameters_from_string_functions()
    {
        $parameters = Parameters::function('is_string');

        $this->assertCount(1, $parameters->names());
        $this->assertTrue($parameters->hasParameter('value'));
        $this->assertEquals($parameters->type('value'), 'mixed');
    }

    /** @test */
    public function it_can_get_parameters_from_static_class_methods()
    {
        $parameters = Parameters::method(Sample::class, 'resetInstanceCount');

        $this->assertCount(1, $parameters->names());
        $this->assertTrue($parameters->hasParameter('initial'));
        $this->assertEquals($parameters->type('initial'), 'int');
    }

    /** @test */
    public function it_can_get_parameters_from_class_methods()
    {
        $parameters = Parameters::method(new Sample(), 'example');

        $this->assertCount(1, $parameters->names());
        $this->assertTrue($parameters->hasParameter('multiplier'));
        $this->assertEquals($parameters->type('multiplier'), 'int');
    }

    /** @test */
    public function it_implements_the_correct_contracts()
    {
        $this->assertArrayHasKey(ParametersContract::class, class_implements(Parameters::class));
    }
}
