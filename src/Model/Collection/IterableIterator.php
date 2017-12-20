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

namespace Spaark\CompositeUtils\Model\Collection;

use ArrayIterator;
use Iterator;
use IteratorAggregate;
use DomainException;
use OuterIterator;

/**
 * Acts as a generic iterator for anything iterable
 *
 * This is useful as the "iterable" type hint accepts both Traversable
 * objects and native arrays, but there is no uniform way to iterate
 * over these in the language. This class accepts iterable values and
 * finds the relavent Iterator for any Iterator, IteratorAggregate or
 * array.
 */
class IterableIterator implements OuterIterator
{
    use OuterIteratorTrait;

    /**
     * The inner iterator that was picked
     *
     * @var Iterator
     */
    private $iterator;

    /**
     * Accepts any iterable value and creates / obtains an inner
     * Iterator for them
     *
     * @param iterable $iterator
     */
    public function __construct(iterable $iterator)
    {
        if (is_array($iterator))
        {
            $this->iterator = new ArrayIterator($iterator);
        }
        elseif ($iterator instanceof Iterator)
        {
            $this->iterator = $iterator;
        }
        elseif ($iterator instanceof IteratorAggregate)
        {
            while (true)
            {
                $newIterator = $iterator->getIterator();

                if ($newIterator instanceof Iterator)
                {
                    $this->iterator = $newIterator;
                    break;
                }
                elseif ($newIterator instanceof IteratorAggregate)
                {
                    $iterator = $newIterator;
                }
                else
                {
                    throw new DomainException
                    (
                        'The given IteratorAggregate\'s ' .
                        'getIterator() method should return an ' .
                        'Iterator or IteratorAggregate, but it did not'
                    );
                }
            }
        }
        else
        {
            throw new DomainException('Unknown type of iterator');
        }
    }
}
