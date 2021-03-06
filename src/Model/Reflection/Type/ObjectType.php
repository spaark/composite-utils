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

use Spaark\CompositeUtils\Model\Collection\ListCollection\FlexibleList;
use Spaark\CompositeUtils\Model\ClassName;

/**
 * Represents a data type which must be an instance of an object
 *
 * @property-read string $classname
 */
class ObjectType extends AbstractType
{
    /**
     * The name of the class this must be an instance of
     *
     * @readable
     * @var ClassName
     */
    protected $classname;

    /**
     * Creates this ObjectType with the given classname
     *
     * @param mixed $class The name of the class this must be an
     *     instance of
     */
    public function __construct($classname)
    {
        $this->classname = $classname instanceof ClassName
            ? $classname
            : new ClassName($classname);
    }

    /**
     * {@inheritDoc}
     */
    public function equals($type) : bool
    {
        if
        (
            $type instanceof ObjectType &&
            $type->classname->equals($this->classname)
        )
        {
            return true;
        }

        return false;
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function __toString() : string
    {
        $return = (string)$this->classname;

        return $return;
    }
}
