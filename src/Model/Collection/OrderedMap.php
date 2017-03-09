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
class OrderedMap extends AbstractMap
{
    private $map;

    private $list;

    public function __construct(AbstractMap $map, AbstractList $list)
    {
        $this->map = $map;
        $this->list = $list;
    }

    /**
     * Adds an element to the Map
     *
     * @param KeyType $key The key to add
     * @param ValueType $value The value to add
     */
    public function insert(Pair $pair)
    {
        if (!$pair instanceof OrderedPair)
        {
            $pair = new OrderedPair
            (
                $this->size(),
                $pair->key,
                $pair->value
            );
        }

        $this->map->insert($pair);
        $this->list->add($pair);
    }

    /**
     * Checks if a key exists
     *
     * @param KeyType $key The key to search for
     * @return boolean
     */
    public function containsKey($key) : bool
    {
        return $this->map->containsKey($key);
    }

    /**
     * Removes an item from the map
     *
     * @param KeyType $key The key of the keypair to remove
     */
    public function remove($key)
    {
        $pair = $this->getPair($key);

        $this->map->remove($pair->key);
        $this->list->remove($pair->index);
    }
            
    /**
     * Gets an item from the map, looking it up by the specified key
     *
     * @param KeyType $key
     * @return OrderedPair
     */
    public function getPair($key) : Pair 
    {
        return is_int($key)
            ? $this->list->get($key)
            : $this->map->getPair($key);
    }

    public function getIterator()
    {
        return $this->map->getIterator();
    }

    public function size() : int
    {
        return $this->list->size();
    }

    public function indexOfKey($key)
    {
        return $this->getPair($key)->index;
    }
}
