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

namespace Spaark\CompositeUtils\Traits;

/**
 * Equatable classes using this trait are deemed equal if they are the
 * same class
 */
trait StaticEquatableTrait
{
    /**
     * {@inheritDoc}
     */
    public function equals($object) : bool
    {
        return $object instanceof static;
    }
}
