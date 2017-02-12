<?php
/**
 * This file is part of the Composite Utils package.
 *
 * (c) Emily Shepherd <emily@emilyshepherd.me>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package spaark/composite-utils
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Test\Traits;

use Spaark\CompositeUtils\Test\Model\PropertyAccessComposite;
use PHPUnit\Framework\TestCase;

class PropertyAccessTraitTest extends TestCase
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
        $this->assertAttributeSame(false, 'c', $testComposite);
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
        $testComposite->a = 5;
    }
}
