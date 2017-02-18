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

use IteratorIterator;
use SplFixedArray;

/**
 * Represents an List stored in a PHP array
 */
class FixedList extends AbstractList
{
    /**
     * @var ValueType[]
     */
    protected $data;

    /**
     * @var int
     */
    protected $pointer = 0;

    public function __construct(int $size = 0)
    {
        $this->data = new SplFixedArray($size);
    }

    /**
     * {@inheritDoc}
     */
    public function push($item)
    {
        $this->data[$this->pointer++] = $item;
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
        $end = $offset + $length;

        for ($i = $offset, $j = 0; $i < $end; $i++, $j++)
        {
            $this->data[$i] = $replacement[$j] ?? null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function set(int $index, $value)
    {
        $this->data[$index] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function remove(int $item)
    {
        $this->set($item, null);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new IteratorIterator($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function size() : int
    {
        return count($this->data);
    }
}

