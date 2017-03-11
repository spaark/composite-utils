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

namespace Spaark\CompositeUtils\Model\Generic;

use Spaark\CompositeUtils\Model\Reflection\Type\AbstractType;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Traits\AllReadableTrait;
use Spaark\CompositeUtils\Traits\AutoConstructTrait;

/**
 */
class GenericContext
{
    use AutoConstructTrait;

    /**
     * @var ObjectType
     * @construct required
     */
    protected $object;

    /**
     * @var ReflectionComposite
     * @construct required
     */
    protected $reflector;

    /**
     * Get the type for the given generic name
     *
     * @param string $name
     * @return AbstractType
     */
    public function getGenericType(string $name) : AbstractType
    {
        return $this->object->generics
        [
            $this->reflector->generics->indexOfKey($name)
        ];
    }
}
