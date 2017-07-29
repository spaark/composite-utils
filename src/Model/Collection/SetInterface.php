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
interface SetInterface extends CollectionInterface
{
    /**
     * Adds a new item to the end of the list
     *
     * @param ValueType $item The item to add
     */
    public function add($item) : bool;

    /**
     * Removes an item from the list, specified by its index
     *
     * @parami ValueType $item The item to remove
     */
    public function remove($item);
}
