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

namespace Spaark\CompositeUtils\Model\Collection;
/**
 * Spaark Framework
 *
 * @author Emily Shepherd <emily@emilyshepherd.me>
 * @copyright 2012-2015 Emily Shepherd
 */

use \Spaark\CompositeUtils\Model\Base\Model;

/**
 * Represents an abstract collection of items
 *
 * This can be interacted with in the same way a PHP array:
 * <pre><code>
 * $a        = new Collection();
 * $a['key'] = 'value';
 * </code></pre>
 *
 * This class, on its own, does not add any new functionality over
 * PHP arrays. It is useful for two purposes: firstly as a means of
 * boxing a PHP array to ensure it is an object for type-hinting.
 * Secondly, other forms of Collection (such as Sets and HashMaps) may
 * extend this class and add their own functionality.
 */
class Collection implements \ArrayAccess, \Iterator, \Countable
{
    /**
     * The raw data of this Collection
     *
     * @var array
     */
    protected $data = array( );

    /**
     * The current position of the array pointer
     *
     * @var int
     */
    protected $pointer = 0;

    /**
     * Returns the current element
     *
     * @return mixed The current element
     */
    public function current()
    {
        return $this->offsetGet($this->pointer);
    }

    /**
     * Returns the current key
     *
     * @return int The current key
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * Advances the internal pointer by one
     */
    public function next()
    {
        $this->pointer++;

        return $this->valid() ? $this->offsetGet($this->pointer) : null;
    }

    /**
     * Checks if the given offset is set
     *
     * @param scalar $offset The offset to check
     * @return boolean True if the offset is set
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Returns the data at the given offset
     *
     * @param scalar $offset The offset to get
     * @return mixed The value at the given offset
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Adds a new key-value pair to the Collection
     *
     * If the key already exists in the collection, its key-value is
     * overwritten.
     *
     * @param scalar $offset The key to set
     * @param mixed $value The value to set
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Removes the given key-value pair from the Collection
     *
     * @param scalar $offset The key to unset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Resets the internal array pointer
     */
    public function rewind()
    {
        $this->pointer = 0;

        return $this->offsetGet(0);
    }

    /**
     * Changes the position of the array pointer
     *
     * @param scalar $pos The position to seek to
     * @return mixed The value at that new point
     */
    public function seek($pos)
    {
        $this->pointer = $pos;

        return $this->offsetGet($pos);
    }

    /**
     * Checks if there are any more elements to be read from the
     * Collection
     *
     * @return boolean True if there are one or more elements
     */
    public function valid()
    {
        return $this->pointer < $this->size();
    }

    /**
     * Adds an element to the Collection, without specifying a key
     *
     * @param scalar $offset The key to set
     * @param mixed $item The element to add
     */
    public function add($offset, $item)
    {
        $this->offsetSet($offset, $item);
    }

    /**
     * Push an element onto the end of the array
     *
     * @param mixed $item The element to add
     */
    public function push($item)
    {
        $this->offsetSet($this->size(), $item);
    }

    /**
     * Returns how many elements are in the Collection
     *
     * @return int The number of elements in the Collection
     */
    public function size()
    {
        return count($this->data);
    }

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
     * Clears the Collection of all data
     */
    public function clear()
    {
        $this->data = array( );
    }

    /**
     * Magic Method which returns the Collection's data when it is
     * passed to var_dump or similar
     *
     * This prevents the Collection from returning the large amount of
     * extra stuff that is contained within Collection, such as the
     * internal pointer and inherited interanal Model elements.
     *
     * @return array The data
     */
    public function __debugInfo()
    {
        return $this->data;
    }
}
