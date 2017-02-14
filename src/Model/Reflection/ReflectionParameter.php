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

namespace Spaark\CompositeUtils\Model\Reflection;

/**
 * Represents a parameter passed to a method
 *
 * @property-read string $name
 * @property-read ReflectionMethod $owner
 * @property-read string $type
 */
class ReflectionParameter extends Reflector
{
    /**
     * The name of the parameter
     *
     * @var string
     */
    protected $name;

    /**
     * The method to which this paramenter is passed
     *
     * @var ReflectionMethod
     */
    protected $owner;

    /**
     * This parameter's type
     *
     * @var string
     */
    protected $type;
}
