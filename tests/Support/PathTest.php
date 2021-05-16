<?php

declare(strict_types=1);

namespace Erik\Raygon\Tests\Support;

use Erik\Raygon\Contracts\Support\Directory;
use Erik\Raygon\Contracts\Support\File;
use Erik\Raygon\Support\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    /** @test */
    public function it_can_show_paths_as_string()
    {
        $path = new Path(__DIR__ . '/PathTest.php');

        $this->assertEquals(__DIR__ . '/PathTest.php', (string) $path);
    }

    /** @test */
    public function it_can_get_file()
    {
        $path = new Path(__DIR__ . '/PathTest.php');

        $this->assertEquals($path->isFile(), true);
        $this->assertTrue($path->file() instanceof File);
        $this->assertEquals(__DIR__ . '/PathTest.php', (string) $path->file());
    }

    /** @test */
    public function it_can_get_directories()
    {
        $path = new Path(__DIR__);

        $this->assertEquals($path->isDirectory(), true);
        $this->assertTrue($path->directory() instanceof Directory);
        $this->assertEquals(__DIR__, (string) $path->directory());
    }

    /** @test */
    public function it_can_get_directories_from_files()
    {
        $path = new Path(__DIR__ . '/PathTest.php');

        $this->assertEquals($path->isFile(), true);
        $this->assertTrue($path->directory() instanceof Directory);
        $this->assertEquals(__DIR__, (string) $path->directory());
    }
}
