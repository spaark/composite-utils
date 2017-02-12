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

namespace Spaark\CompositeUtils\Test\Model\Collection;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Model\Collection\Collection;

class CollectionTest extends TestCase
{
    public function testEmpty()
    {
        $collection = new Collection();
        $this->assertEquals(0, $collection->size());
        $this->assertEquals(0, $collection->count());
        $this->assertTrue($collection->empty());
    }

    public function testAdd()
    {
        $collection = new Collection();
        $collection->push('123');
        $this->assertFalse($collection->empty());
    }

    public function testOffsetGet()
    {
        $collection = new Collection();
        $collection->push('123');
        $this->assertEquals('123', $collection->offsetGet(0));
        $this->assertEquals('123', $collection[0]);
    }
}
