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

use ArrayIterator;
use DomainException;
use EmptyIterator;
use PHPUnit\Framework\TestCase;
use Spaark\CompositeUtils\Model\Collection\IterableIterator;
use Spaark\CompositeUtils\Test\Model\IteratorAggregate;

/**
 * Tests that the IterableIterator can support any type of iterable
 */
class IterableIteratorTest extends TestCase
{
    /**
     * Iterates over an iterator and checks its elements are as
     * expected
     */
    private function runIterateTest(IterableIterator $test)
    {
        $expect = ['a', 'b'];

        foreach ($test as $i => $value)
        {
            $this->assertEquals($expect[$i], $value);
        }
    }

    /**
     * Tests that IterableIterator works correctly with an array and
     * creates an ArrayIterator for its inner iterator
     */
    public function testWithArray()
    {
        $test = new IterableIterator(['a', 'b']);
        $this->assertInstanceOf(
            ArrayIterator::class,
            $test->getInnerIterator()
        );
        $this->runIterateTest($test);
    }

    /**
     * Tests that IterableIterator uses an Iterator as is as its
     * internal iterator
     */
    public function testWithIterator()
    {
        $iterator = new EmptyIterator();
        $test = new IterableIterator($iterator);
        $this->assertSame($iterator, $test->getInnerIterator());
    }

    /**
     * Tests that IterableIterator uses an IteratorAggregate's Iterator
     * as its internal iterator
     */
    public function testWithIteratorAggregate()
    {
        $iterator = new IteratorAggregate(new EmptyIterator());
        $test = new IterableIterator($iterator);

        $this->assertSame(
            $iterator->getIterator(),
            $test->getInnerIterator()
        );
    }

    /**
     * Tests that IterableIterator keeps recursing if the
     * IteratorAggregate's getIterator() method returns another
     * IteratorAggregate
     */
    public function testWithRecursiveIteratorAggregate()
    {
        $innerIterator = new EmptyIterator();
        $iterator = new IteratorAggregate(
            new IteratorAggregate(
                new IteratorAggregate(
                    $innerIterator
                )
            )
        );
        $test = new IterableIterator($iterator);

        $this->assertSame(
            $innerIterator,
            $test->getInnerIterator()
        );
    }

    /**
     * Tests that the IterableIterator throws an exception if the given
     * IteratorAggregate's getIterator() method does not return a
     * Traversable object
     */
    public function testBrokenGetIterator()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'The given IteratorAggregate\'s getIterator() method ' .
            'should return an Iterator or IteratorAggregate, but it ' .
            'did not'
        );

        new IterableIterator(new IteratorAggregate(null));
    }
}
