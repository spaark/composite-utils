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

namespace Spaark\CompositeUtils\Model\Collection\Transformation;

use OuterIterator;
use Spaark\CompositeUtils\Model\Collection\IterableIterator;
use Spaark\CompositeUtils\Model\Collection\OuterIteratorTrait;

/**
 * A Transformation represents an iterator which iterates over something
 * iterable and transforms it in some way
 *
 * Notable and common examples are: maps, filters and splicing
 */
abstract class Transformation implements OuterIterator
{
    use OuterIteratorTrait;

    /**
     * The inner iterator being transformed
     *
     * @var IterableIterator
     * @construct required
     */
    protected $iterator;

    /**
     * The current key
     *
     * @var scalar
     */
    protected $key;

    /**
     * The current value
     *
     * @var mixed
     */
    protected $current;

    /**
     * Constructs the Transformation with the iterable original data
     */
    public function __construct(iterable $iterator)
    {
        $this->iterator = new IterableIterator($iterator);
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->iterator->rewind();
        $this->doProcess();
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        $this->iterator->next();
        $this->doProcess();
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Calls the child class' process() method if and only if this is
     * valid()
     */
    private function doProcess()
    {
        if ($this->valid())
        {
            $this->process();
        }
    }

    /**
     * Called on whenever the pointer is moved (on rewind() or next()
     * calls) to process the current position place
     */
    abstract protected function process();
}
