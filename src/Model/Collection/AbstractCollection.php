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

/**
 * Represents an abstract collection of items
 *
 * @generic ValueType
 */
abstract class AbstractCollection
    implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * Returns the size of the collection
     *
     * @return int The size of the collection
     */
    abstract public function size() : int;

    /**
     * Returns how many elements are in the Collection
     *
     * @return int The number of elements in the Collection
     */
    public function count()
    {
        return $this->size();
    }

    /**
     * Checks if the Collection is empty
     *
     * @return boolean True if the element is empty
     */
    public function empty()
    {
        return $this->size() === 0;
    }

    /**
     * Basic implementation of contains
     *
     * Should be overridden by datatype-specific implementations for
     * speed improvements
     *
     * @param ValueType $searchFor The key to search for
     * @return boolean
     */
    public function contains($searchFor) : bool
    {
        foreach ($this as $item)
        {
            if ($item === $searchFor)
            {
                return true;
            }
        }

        return false;
    }

    public function map(callable $cb)
    {
        foreach ($this as $key => $value)
        {
            $cb($key, $value);
        }
    }
}
