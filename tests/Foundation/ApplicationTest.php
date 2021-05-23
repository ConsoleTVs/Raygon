<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Container;

use PHPUnit\Framework\TestCase;
use Erik\Raygon\Contracts\Foundation\Application as ApplicationContract;
use Erik\Raygon\Foundation\Application;
use Erik\Raygon\Tests\Fixtures\ExampleServiceProvider;
use Erik\Raygon\Tests\Fixtures\Sample;
use Erik\Raygon\Tests\Fixtures\SampleBootstrapper;

class ApplicationTest extends TestCase
{
    /** @test */
    public function it_can_create_a_global_application()
    {
        $app = Application::global(__DIR__);

        $this->assertTrue($app::getGlobal() === $app);
    }

    /** @test */
    public function it_can_register_service_providers()
    {
        $app = Application::global(__DIR__);

        $app->register(ExampleServiceProvider::class);

        $app->boot();

        $instance = $app->make(Sample::class);

        $this->assertTrue($instance instanceof Sample);
        $this->assertEquals($instance->name, 'Erik');
    }

    /** @test */
    public function it_can_boot_with_bootstrappers()
    {
        $app = Application::global(__DIR__);

        $app->boot([SampleBootstrapper::class]);

        $instance = $app->make(Sample::class);

        $this->assertTrue($instance instanceof Sample);
        $this->assertEquals($instance->name, 'Erik');
    }

    /** @test */
    public function it_implements_the_correct_contracts()
    {
        $this->assertArrayHasKey(ApplicationContract::class, class_implements(Application::class));
    }
}
