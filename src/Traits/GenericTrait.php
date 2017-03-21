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

use Spaark\CompositeUtils\Factory\Reflection\TypeParser;
use Spaark\CompositeUtils\Model\Generic\GenericContext;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Exception\ImmutablePropertyException;

/**
 * Classes using this trait can have generic properties
 *
 * All generated generics will automatically use this Generic, but you
 * may also use this in the root class itself as it provides helpers
 * for creating generic instances
 */
trait GenericTrait
{
    use HasReflectorTrait;

    /**
     * @var GenericContext
     */
    protected $genericContext;

    /**
     * @var ObjectType
     */
    protected $objectType;

    /**
     * Gets the object descriptor for this class
     *
     * @return ObjectType
     */
    public function getObjectType() : ObjectType
    {
        if (!$this->objectType)
        {
            $this->objectType =
                (new TypeParser())->parseFromType($this);
        }

        return $this->objectType;
    }

    /**
     * Gets the generic context for this class or creates one if it does
     * not exist
     *
     * @return GenericContext
     */
    public function getGenericContext() : GenericContext
    {
        if (!$this->genericContext)
        {
            $this->genericContext = new GenericContext
            (
                $this->getObjectType(),
                static::getReflectionComposite()
            );
        }

        return $this->genericContext;
    }

    /**
     * Sets the generic context for this class if one does not already
     * exist
     *
     * @param GenericContext $genericContext
     */
    public function setGenericContext(GenericContext $genericContext)
        : void
    {
        if ($this->genericContext)
        {
            throw new ImmutablePropertyException
            (
                (string)$this->getObjectType(),
                'genericContext'
            );
        }

        $this->genericContext = $genericContext;
        $this->objectType = $genericContext->object;
    }

    /**
     * Gets an instance of a generic
     *
     * @param string $name
     * @return mixed
     */
    protected function getInstanceOfGeneric(string $name)
    {
        $class =
            (string)$this->getGenericContext()->getGenericType($name);

        return new $class();
    }
}
