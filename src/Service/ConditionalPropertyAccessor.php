<?php

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
