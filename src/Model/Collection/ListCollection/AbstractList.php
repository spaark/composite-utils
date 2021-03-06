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

namespace Spaark\CompositeUtils\Model\Collection\ListCollection;

use Spaark\CompositeUtils\Model\Collection\AbstractCollection;

/**
 * Represents an abstract collection which acts as a list of items
 */
abstract class AbstractList
    extends AbstractCollection
    implements ArrayList
{
    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null)
        {
            $this->add($value);
        }
        else
        {
            $this->set($offset, $value);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function set(int $index, $value)
    {
        $this->splice($offset, 0, [$value]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($index)
    {
        $this->remove($index);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($index)
    {
        return $index < $this->size();
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($index)
    {
        return $this->get($index);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(int $index)
    {
        $this->splice($index, 1);
    }
}

