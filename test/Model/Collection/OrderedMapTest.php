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
use Spaark\CompositeUtils\Model\Collection\Map\HashMap;
use Spaark\CompositeUtils\Model\Collection\Map\OrderedMap;
use Spaark\CompositeUtils\Model\Collection\ListCollection\FlexibleList;

class OrderedMapTest extends TestCase
{
    public function testEmpty()
    {
        $collection = new OrderedMap(new HashMap(), new FlexibleList());
        $this->assertEquals(0, $collection->size());
        $this->assertEquals(0, $collection->count());
        $this->assertTrue($collection->empty());

        foreach ($collection as $item)
        {
            $this->fail();
        }
    }

    public function testAdd()
    {
        $collection = new OrderedMap(new HashMap(), new FlexibleList());
        $collection->add('sds', '123');
        $this->assertFalse($collection->empty());
    }

    public function testOffsetGet()
    {
        $collection = new OrderedMap(new HashMap(), new FlexibleList());
        $collection->add('foo', '123');
        $this->assertEquals('123', $collection->get('foo'));

        $collection['foo'] = '456';
        $this->assertEquals('456', $collection['foo']);
    }

    public function testRemove()
    {
        $collection = new OrderedMap(new HashMap(), new FlexibleList());
        $collection['foo'] = true;
        $collection['bar'] = false;

        $this->assertEquals(2, $collection->size());

        $collection->remove('foo');

        $this->assertEquals(1, $collection->size());
    }

    public function testContains()
    {
        $collection = new OrderedMap(new HashMap(), new FlexibleList());
        $this->assertFalse($collection->contains('foo'));

        $collection['asa'] = 'foo';
        $this->assertTrue($collection->contains('foo'));
    }

    public function testContainsKey()
    {
        $collection = new OrderedMap(new HashMap(), new FlexibleList());
        $this->assertFalse($collection->containsKey('bar'));

        $collection['bar'] = '1234';
        $this->assertTrue($collection->containsKey('bar'));
    }

    public function testLoop()
    {
        $collection = new OrderedMap(new HashMap(), new FlexibleList());
        $collection['foo'] = '123';
        $collection['bar'] = '456';
        $collection['baz'] = '789';

        $items =
        [
            'foo' => '123',
            'bar' => '456',
            'baz' => '789'
        ];

        foreach ($collection as $key => $item)
        {
            $this->assertTrue(isset($items[$key]));
            $this->assertEquals($items[$key], $item);
        }
    }
}
