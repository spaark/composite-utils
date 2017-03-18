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

use ArrayAccess;
use IteratorAggregate;
use Countable;

/**
 * Represents an abstract collection of items
 *
 * @generic ValueType
 */
interface CollectionInterface
    extends ArrayAccess, IteratorAggregate, Countable
{
    /**
     * Returns the size of the collection
     *
     * @return int The size of the collection
     */
    public function size() : int;

    /**
     * Checks if the Collection is empty
     *
     * @return boolean True if the element is empty
     */
    public function empty();

    /**
     * Basic implementation of contains
     *
     * Should be overridden by datatype-specific implementations for
     * speed improvements
     *
     * @param ValueType $searchFor The key to search for
     * @return boolean
     */
    public function contains($searchFor) : bool;

    public function map(callable $cb);

    /**
     * Returns the data in this collection as a native PHP array
     *
     * @return array
     */
    public function toArray() : array;
}
