<?php

namespace Spaark\CompositeUtils\Test\Traits;

use Spaark\CompositeUtils\Test\Model\PropertyAccessComposite;

class PropertyAccessTraitTest
{
    public function testRead()
    {
        $testComposite = new PropertyAccessComposite();

        $this->assertEquals('123', $testComposite->a);
        $this->assertTrue($testComposite->c);
    }

    public function testWrite()
    {
        $testComposite = new PropertyAccessComposite();

        $testComposite->b = '00000456';
        $this->assertAttributeSame(456, 'b', $testComposite);

        $testComposite->c = 0;
        $this->assertAttributeFalse('c', $testComposite);
    }

    /**
     * @expectedException Spaark\CompositeUtils\Exception\PropertyNotReadableException
     */
    public function testIllegalRead()
    {
        (new PropertyAccessComposite())->b;
    }

    /**
     * @expectedException Spaark\CompositeUtils\Exception\PropertyNotWritableException
     */
    public function testIllegalWrite()
    {
        $testComposite = new PropertyAccessComposite();
        $testComposte->a = 5;
    }
}
