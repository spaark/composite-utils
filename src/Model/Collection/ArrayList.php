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

/**
 * Represents an List stored in a PHP array
 */
class ArrayList extends AbstractList
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
        $this->data[] = $item;
    }

    /**
     * {@inheritDoc}
     */
    public function get(int $index)
    {
        return $this->data[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function splice
    (
        int $offset,
        ?int $length = null,
        array $replacement = []
    )
    {
        array_splice($this->data, $offset, $length, $replacement);
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

