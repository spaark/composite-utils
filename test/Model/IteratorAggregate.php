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

namespace Spaark\CompositeUtils\Test\Model;

use Traversable;
use IteratorAggregate as NativeIteratorAggregate;

/**
 * Sample IteratorAggregate used in tests which just returns whatever
 * Traversable was set during construction
 */
class IteratorAggregate implements NativeIteratorAggregate
{
    /**
     * An iterator just used for testing that this is correctly used
     *
     * @var Traversable
     */
    private $iterator;

    /**
     * Creates a dud internal iterator
     */
    public function __construct($iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * Returns the dud internal iterator
     *
     * @return Traversable
     */
    public function getIterator()
    {
        return $this->iterator;
    }
}
