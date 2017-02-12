<?php

namespace Spaark\CompositeUtils\Exception;

use \Exception;

class IllegalPropertyTypeException extends Exception
{
    public function __construct
    (
        string $class,
        string $property,
        string $expected,
        string $got,
        Exception $previous = null
    )
    {
        parent::__construct
        (
              'Tried to set an illegal property type for '
            . $class .'::$' . $property . '. Excpected ' . $expected
            . ', got ' . $got,
            0,
            $previous
        );
    }
}
