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

/**
 * A MapTransformation runs each element in an iterable collection
 * through a callback, and replaces that element with the result of that
 * call
 */
class MapTransformation extends CallbackTransformation
{
    /**
     * Processes the current value by running it through the callback
     */
    protected function process()
    {
        $this->key = $this->iterator->key();
        $this->current = $this->call();
    }
}
