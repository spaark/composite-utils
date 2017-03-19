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

namespace Spaark\CompositeUtils\Model\Collection\Map;

/**
 * Iterator for the HashMap datatype
 *
 * @generic KeyType
 * @generic ValueType
 */
class HashMapIterator extends MapIterator
{
    /**
     * @var Pair<KeyType, ValueType>[]
     * @construct required
     */
    protected $data;

    /**
     * @var Pair<KeyType, ValueType>[]
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrent() : Pair
    {
        return current($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return key($this->data) !== NULL;
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        $next = next($this->data);

        return $next ? $next->value : null;
    }
}
