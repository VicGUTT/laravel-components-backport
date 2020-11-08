<?php

namespace VicGutt\ComponentBackport\Tests\View\Blade;

class BladeEndSectionsTest extends AbstractBladeTestCase
{
    public function testEndSectionsAreCompiled()
    {
        $this->assertSame('<?php $__env->stopSection(); ?>', $this->compiler->compileString('@endsection'));
    }
}
