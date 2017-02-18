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
use Spaark\CompositeUtils\Model\Collection\FixedList;

class FixedListTest extends TestCase
{
    public function testEmpty()
    {
        $collection = new FixedList();
        $this->assertEquals(0, $collection->size());
        $this->assertEquals(0, $collection->count());
        $this->assertTrue($collection->empty());

        foreach ($collection as $item)
        {
            $this->fail();
        }
    }

    public function testFixedSize()
    {
        $collection = new FixedList(10);
        $this->assertEquals(10, $collection->size());

        for ($i = 0; $i < 10; $i++)
        {
            $collection->add($i);
        }

        $this->assertEquals(10, $collection->size());
        $collection->remove(5);
        $this->assertEquals(10, $collection->size());
    }

    public function testResize()
    {
        $collection = new FixedList(10);
        $collection->resize(20);

        $this->assertEquals(20, $collection->size());
    }

    public function testSet()
    {
        $collection = new FixedList(5);
        $collection->set(3, 'test');
        $this->assertNull($collection[0]);
        $this->assertNull($collection[1]);
        $this->assertNull($collection[2]);
        $this->assertSame('test', $collection[3]);
        $this->assertNull($collection[4]);
    }

    public function testOffsetGet()
    {
        $collection = new FixedList(2);
        $collection->add('123');
        $this->assertEquals('123', $collection->get(0));

        $collection[] = '456';
        $this->assertEquals('456', $collection[1]);
    }

    public function testContains()
    {
        $collection = new FixedList(1);
        $this->assertFalse($collection->contains('foo'));

        $collection[] = 'foo';
        $this->assertTrue($collection->contains('foo'));
    }

    public function testLoop()
    {
        $collection = new FixedList(3);
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
