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

use Spaark\CompositeUtils\Traits\StaticEquatableTrait;

/**
 * Represents a type which has a null value
 */
class NullType extends NativeType
{
    use StaticEquatableTrait;

    const NAME = 'Null';

    /**
     * {@inheritDoc}
     */
    protected $nullable = true;
}
