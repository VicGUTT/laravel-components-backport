<?php

namespace VicGutt\ComponentBackport\Tests\View;

use Illuminate\Filesystem\Filesystem;
use VicGutt\ComponentBackport\View\Engines\PhpEngine;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ViewPhpEngineTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testViewsMayBeProperlyRendered()
    {
        $engine = new PhpEngine(new Filesystem);
        $this->assertSame('Hello World
', $engine->get(__DIR__.'/fixtures/basic.php'));
    }
}
