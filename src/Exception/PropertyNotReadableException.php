<?php

namespace Spaark\CompositeUtils\Exception;

use \Exception;

class PropertyNotReadableException extends Exception
{
    public function __construct($class, $property, $previous = null)
    {
        parent::__construct
        (
              'Tried to read unreadable property '
            . $class . '::$' . $property,
            0,
            $previous
        );
    }
}
