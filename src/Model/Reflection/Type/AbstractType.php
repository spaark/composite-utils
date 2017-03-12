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

use Spaark\CompositeUtils\Traits\AllReadableTrait;
use Spaark\CompositeUtils\Model\Common\Equatable;

/**
 * Abstract class extended by composites representing data types
 *
 * @property-read boolean $nullable
 * @implements Equatable<AbstractType>
 */
abstract class AbstractType implements Equatable
{
    use AllReadableTrait;

    /**
     * Is this type nullable
     *
     * @readable
     * @var boolean
     */
    protected $nullable = false;

    abstract function __toString() : string;
}
