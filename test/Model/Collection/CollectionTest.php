<?php

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
