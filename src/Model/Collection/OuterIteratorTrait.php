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

use Iterator;

/**
 * A trait which implements all of the methods required by the
 * OuterIterator trait
 *
 * The default implementations simply pass through all requests onto
 * the inner iterator.
 */
trait OuterIteratorTrait
{
    /**
     * Advances the inner iterator's pointer
     */
    public function next()
    {
        return $this->iterator->next();
    }

    /**
     * Returns the inner iterator's current value
     *
     * @return mixed
     */
    public function current()
    {
        return $this->iterator->current();
    }

    /**
     * Returns the inner iterator's current key
     *
     * @return scalar
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     * Resets the inner iterator
     */
    public function rewind()
    {
        return $this->iterator->rewind();
    }

    /**
     * Returns if the inner iterator is still valid
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->iterator->valid();
    }

    /**
     * Returns the inner iterator
     *
     * @return Iterator
     */
    public function getInnerIterator() : Iterator
    {
        return $this->iterator;
    }
}
