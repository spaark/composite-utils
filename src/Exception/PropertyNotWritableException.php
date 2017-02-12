<?php

namespace Spaark\CompositeUtils\Exception;

use \Exception;

class PropertyNotWritableException extends Exception
{
    public function __construct($class, $property, $previous = null)
    {
        parent::__construct
        (
              'Tried to write to unwritable property '
            . $class . '::$' . $property,
            0,
            $previous
        );
    }
}
