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
 * Represents an abstract collection which acts as a list of items
 */
abstract class AbstractSet
    extends AbstractCollection
    implements SetInterface
{
    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null)
        {
            $this->push($value);
        }
        else
        {
            throw new \Exception();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($index)
    {
        throw new \Exception();
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($index)
    {
        throw new \Exception();
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($index)
    {
        throw new \Exception();
    }
}

