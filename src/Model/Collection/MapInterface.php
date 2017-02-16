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
interface MapInterface extends CollectionInterface
{
    /**
     * Insert a new key value Pair into the Map
     *
     * @param Pair<KeyType, ValueType> $pair
     */
    public function insert(Pair $pair);

    /**
     * Gets the item, looking it up with the specified key
     *
     * @param KeyType $item The key to search by
     * @return ValueType The item
     */
    public function get($item);

    /**
     * Removes an item, looking it up with the specified key
     *
     * @param KeyType $item The key of the keypair to remove
     */
    public function remove($item);

    /**
     * Checks if the specified key exists in this map
     *
     * @param KeyType $key The key to search for
     * @return boolean
     */
    public function containsKey($key) : bool;

    /**
     * Adds an element to the Map
     *
     * @param KeyType $key The key to add
     * @param ValueType $value The value to add
     */
    public function add($key, $value);
}
