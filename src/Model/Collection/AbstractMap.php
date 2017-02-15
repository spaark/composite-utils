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
 * Represents an abstract collection which maps one value to another
 *
 * These are stored as pairs
 *
 * @generic KeyType
 * @generic ValueType
 */
abstract class AbstractMap extends AbstractCollection
{
    /**
     * Insert a new key value Pair into the Map
     *
     * @param Pair<KeyType, ValueType> $pair
     */
    abstract public function insert(Pair $pair);

    /**
     * Gets the item, looking it up with the specified key
     *
     * @param KeyType $item The key to search by
     * @return ValueType The item
     */
    abstract public function get($item);

    /**
     * Removes an item, looking it up with the specified key
     *
     * @param KeyType $item The key of the keypair to remove
     */
    abstract public function remove($item);

    /**
     * Checks if the specified key exists in this map
     *
     * @param KeyType $key The key to search for
     * @return boolean
     */
    abstract public function containsKey($key) : bool;

    /**
     * Adds an element to the Map
     *
     * @param KeyType $key The key to add
     * @param ValueType $value The value to add
     */
    public function offsetSet($key, $value)
    {
        $this->add($key, $value);
    }

    /**
     * Adds an element to the Map
     *
     * @param KeyType $key The key to add
     * @param ValueType $value The value to add
     */
    public function add($key, $value)
    {
        $this->insert(new Pair($key, $value));
    }

    /**
     * Checks if a key exists
     *
     * @param KeyType $key The key to search for
     * @return boolean
     */
    public function offsetExists($key) : bool
    {
        return $this->contains($key);
    }

    /**
     * Removes an item from the map
     *
     * @param KeyType $key The key of the keypair to remove
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }

    /**
     * Gets an item from the map, looking it up by the specified key
     *
     * @param KeyType $key
     * @return ValueType
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }
}
