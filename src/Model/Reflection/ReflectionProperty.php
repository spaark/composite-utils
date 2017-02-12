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
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Model\Reflection;
/**
 *
 *
 */

use Spaark\CompositeUtils\Model\Reflection\Type;

/**
 * Reflects upon properies within a model, and parses their doc comments
 */
class ReflectionProperty extends Reflector
{
    /**
     * The name of this property
     *
     * @var string
     */
    protected $name;

    /**
     * The Composite that this property belongs to
     *
     * @var ReflectionComposite
     */
    protected $owner;

    /**
     * Is this property readable?
     *
     * @var bool
     * @readable
     */
    protected $readable = false;

    /**
     * Is this property writable?
     *
     * @var bool
     * @readable
     */
    protected $writable = false;

    /**
     * This property's type
     *
     * @var AbstractType
     * @readable
     */
    protected $type;

    /**
     * This property's default value
     *
     * @readable
     * @var mixed
     */
    protected $defaultValue;

    /**
     * Is this property passed to the constructor
     *
     * @var boolean
     */
    protected $passedToConstructor;

    /**
     * Is this property required by the constructor
     *
     * @var boolean
     */
    protected $requiredInConstructor;

    /**
     * Is this property built in the constructor
     *
     * @var boolean
     */
    protected $builtInConstructor;

    /**
     * @getter
     */
    public function isProperty()
    {
        return (boolean)$this->type;
    }
}
