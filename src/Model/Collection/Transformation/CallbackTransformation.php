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
 * A CallbackTransformation is a Transformation which accepts some form
 * of callback during instanciation which is used during the
 * transformation
 *
 * Notable and common examples are the map and filter transformations.
 * Something like a splice transformation, for example, does NOT use a
 * callback.
 */
abstract class CallbackTransformation extends Transformation
{
    /**
     * @construct required
     */
    protected $transform;

    /**
     * Constructs the CallbackTransformation with the iterable original
     * data and a callback
     */
    public function __construct(iterable $iterator, callable $cb)
    {
        parent::__construct($iterator);

        $this->transform = $cb;
    }

    /**
     * Calls the callback with the current value and key, returning its
     * return value unmodified
     *
     * @return mixed
     */
    protected function call()
    {
        $cb = $this->transform;
        return $cb($this->iterator->current(), $this->iterator->key());
    }
}
