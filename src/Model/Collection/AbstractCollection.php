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
abstract class AbstractCollection implements CollectionInterface
{
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
     * {@inheritDoc}
     */
    public function empty() : bool
    {
        return $this->size() === 0;
    }

    /**
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     */
    public function map(callable $cb)
    {
        foreach ($this as $key => $value)
        {
            $cb($key, $value);
        }
    }
}
