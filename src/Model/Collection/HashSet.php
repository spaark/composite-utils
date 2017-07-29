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
use Spaark\CompositeUtils\Service\HashProducer;

/**
 * Represents an List stored in a PHP array
 */
class HashSet extends AbstractSet
{
    /**
     * @var ValueType[]
     */
    protected $data = [];

    /**
     * {@inheritDoc}
     */
    public function add($item)
    {
        $this->data[HashProducer::getHash($item)] = $item;
    }

    /**
     * Checks if an element exists
     *
     * @param ValueType The item to search for
     * @return boolean If the item exists
     */
    public function contains($item) : bool
    {
        return isset($this->data[HashProducer::getHash($item)]);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($item)
    {
        unset($this->data[HashProducer::getHash($item)]);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function size() : int
    {
        return count($this->data);
    }
}

