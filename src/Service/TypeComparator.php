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

namespace Spaark\CompositeUtils\Service;

use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\MixedType;
use Spaark\CompositeUtils\Model\Reflection\Type\AbstractType;
use Spaark\CompositeUtils\Model\Reflection\Type\ScalarType;

/**
 * Compares two AbstractTypes to check if they are compatible
 */
class TypeComparator
{
    /**
     * Compares two AbstractTypes, ensuring they are compatible
     *
     * @param AbstractType $parent
     * @param AbstractType $child
     * @return boolean
     */
    public function compatible
    (
        AbstractType $parent,
        AbstractType $child
    )
    : bool
    {
        if ($parent instanceof MixedType)
        {
            return true;
        }
        elseif ($parent instanceof ScalarType)
        {
            return get_class($parent) === get_class($child);
        }
        elseif ($parent instanceof ObjectType)
        {
            return
                $child instanceof ObjectType && 
                is_a
                (
                    $child->classname->__toString(),
                    $parent->classname->__toString(),
                    true
                );
        }

        throw new \DomainException
        (
            'Unknown type: ' . get_class($parent)
        );
    }
}
