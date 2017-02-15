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

use Spaark\CompositeUtils\Model\Reflection\ReflectionProperty;
use Spaark\CompositeUtils\Exception\PropertyNotWritableException;
use Spaark\CompositeUtils\Exception\PropertyNotReadableException;

/**
 * This class is used to access properties of a composite, enforcing
 * data type requirements and access permissions
 */
class ConditionalPropertyAccessor extends PropertyAccessor
{
    /**
     * {@inheritDoc}
     */
    protected function setAnyValue(ReflectionProperty $property, $value)
    {
        if ($property->writable)
        {
            parent::setAnyValue($property, $value);
        }
        else
        {
            throw new PropertyNotWritableException
            (
                get_class($this->object),
                $property->name
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(string $property)
    {
        if (!$this->reflect->properties->containsKey($property))
        {
            throw new CannotReadPropertyException
            (
                get_class($this->object),
                $property
            );
        }
        elseif (!$this->reflect->properties[$property]->readable)
        {
            throw new PropertyNotReadableException
            (
                get_class($this->object),
                $property
            );
        }

        return parent::getValue($property);
    }
}
