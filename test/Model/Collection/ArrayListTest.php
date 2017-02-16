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
 * @author Emily Shepherd <emily@emilyshepherd.me>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Test\Model\Collection;

use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Model\Collection\ArrayList;

class ArrayListTest extends TestCase
{
    public function testEmpty()
    {
        $collection = new ArrayList();
        $this->assertEquals(0, $collection->size());
        $this->assertEquals(0, $collection->count());
        $this->assertTrue($collection->empty());

        foreach ($collection as $item)
        {
            $this->fail();
        }
    }

    public function testPush()
    {
        $collection = new ArrayList();
        $collection->push('Value');
        $this->assertFalse($collection->empty());
        $this->assertEquals(1, $collection->size());
    }

    public function testOffsetGet()
    {
        $collection = new ArrayList();
        $collection->push('123');
        $this->assertEquals('123', $collection->get(0));

        $collection[] = '456';
        $this->assertEquals('456', $collection[1]);
    }

    public function testRemove()
    {
        $collection = new ArrayList();
        $collection[] = true;
        $collection[] = false;

        $this->assertEquals(2, $collection->size());

        $collection->remove(0);

        $this->assertEquals(1, $collection->size());
    }

    public function testContains()
    {
        $collection = new ArrayList();
        $this->assertFalse($collection->contains('foo'));

        $collection[] = 'foo';
        $this->assertTrue($collection->contains('foo'));
    }

    public function testLoop()
    {
        $collection = new ArrayList();
        $collection[] = '123';
        $collection[] = '456';
        $collection[] = '789';

        $items =
        [
            '123',
            '456',
            '789'
        ];

        foreach ($collection as $key => $item)
        {
            $this->assertTrue(isset($items[$key]));
            $this->assertEquals($items[$key], $item);
        }
    }
}
