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
use Spaark\CompositeUtils\Model\Collection\HashMap;

class HashMapTest extends TestCase
{
    public function testEmpty()
    {
        $collection = new HashMap();
        $this->assertEquals(0, $collection->size());
        $this->assertEquals(0, $collection->count());
        $this->assertTrue($collection->empty());
    }

    public function testAdd()
    {
        $collection = new HashMap();
        $collection->add('sds', '123');
        $this->assertFalse($collection->empty());
    }

    public function testOffsetGet()
    {
        $collection = new HashMap();
        $collection->add('foo', '123');
        $this->assertEquals('123', $collection->offsetGet('foo'));
        $this->assertEquals('123', $collection['foo']);
    }

    public function testLoop()
    {
        $collection = new HashMap();
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
