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

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Test\Model\AutoConstructComposite;
use Spaark\CompositeUtils\Test\Model\TestEntity;
use Spaark\CompositeUtils\Model\Collection\Collection;

class AutoConstructTrait extends TestCase
{
    public function testConstruct()
    {
        $test = new TestEntity();
        $testComposite = new AutoConstructComposite($test);
        $this->assertSame($test, $testComposite->b);
        $this->assertInstanceOf(Collection::class, $testComposite->a);
        $this->assertInstanceOf(TestEntity::class, $testComposite->d);
    }

    /**
     * @expectedException Spaark\CompositeUtils\Exception\MissingRequiredParameterException
     */
    public function testMissingRequired()
    {
        new AutoConstructComposite();
    }

    public function testOptional()
    {
        $test = new TestEntity();
        $test2 = new TestEntity();
        $testComposite = new AutoConstructComposite
        (
            $test,
            true,
            $test2
        );
        $this->assertSame($test, $testComposite->b);
        $this->assertSame($test2, $testComposite->d);
        $this->assertTrue($testComposite->c);
    }
}
