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

namespace Spaark\CompositeUtils\Model\Reflection\Type;

/**
 * Represents all Native PHP Types, extended by more specific types
 *
 * @property-read boolean $nullable
 */
abstract class NativeType extends AbstractType
{
    public function __toString() : string
    {
        return static::NAME;
    }
}
