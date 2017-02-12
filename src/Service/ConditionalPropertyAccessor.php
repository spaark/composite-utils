<?php
/**
 * This file is part of the Composite Utils package.
 *
 * (c) Emily Shepherd <emily@emilyshepherd.me>
 *
 * For the full copyright and licence information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package spaark/composite-utils
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Service;

use Spaark\CompositeUtils\Exception\PropertyNotWritableException;
use Spaark\CompositeUtils\Exception\PropertyNotReadableException;

class ConditionalPropertyAccessor extends PropertyAccessor
{
    protected function setAnyValue($property, $value)
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

    public function getValue($property)
    {
        if (!$this->reflect->properties->contains($property))
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