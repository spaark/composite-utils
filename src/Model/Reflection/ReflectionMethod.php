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

use Spaark\CompositeUtils\Model\Colection\FixedList;

/**
 * Represents a method
 *
 * @property-read string $name
 * @property-read ReflectionComposite $owner
 * @property-read HashMap $parameters
 * @property-read Visibility $visibility
 * @property-read Scope scope
 * @property-read boolean $final
 */
class ReflectionMethod extends Reflector
{
    /**
     * The name of the method
     *
     * @var string
     */
    protected $name;

    /**
     * The composite in which this method was defined
     *
     * @var ReflectionComposite
     */
    protected $owner;

    /**
     * The parameters passed to this method
     *
     * @var FixedList
     */
    protected $parameters;

    /**
     * The method's visibility
     *
     * @var Visibility
     */
    protected $visbility;

    /**
     * The method's scope
     *
     * @var Scope
     */
    protected $scope;

    /**
     * Whether this method is declared final
     *
     * @var boolean
     */
    protected $final;
}

