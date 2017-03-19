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

use Spaark\CompositeUtils\Traits\AutoConstructTrait;

/**
 * Abstract Iterator for Map datatypes
 *
 * @generic KeyType
 * @generic ValueType
 */
abstract class MapIterator implements \Iterator
{
    /**
     * Returns the current pair in the Map
     *
     * @return Pair<KeyType, ValueType>
     */
    abstract protected function getCurrent() : Pair;

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->getCurrent()->value;
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->getCurrent()->key;
    }
}
