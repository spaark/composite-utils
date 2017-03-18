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
 * This trait is used as a shortcut when you need to use both the
 * PropertyAccessTrait and AutoConstructTrait
 *
 * It exists to solve a bug in the current version of PHP; as both
 * traits use the HasReflectorTrait, this causes a trait conflict
 */
trait AutoConstructPropertyAccessTrait
{
    use AutoConstructTrait, PropertyAccessTrait
    {
        AutoConstructTrait::getReflectionComposite insteadof
            PropertyAccessTrait;
        AutoConstructTrait::setDefaultReflectionComposite insteadof
            PropertyAccessTrait;
    }
}
