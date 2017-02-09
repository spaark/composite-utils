<?php

namespace Spaark\Core\Exception;

class NonExistentPropertyException extends \Exception
{
    const ACCESS_TYPE = 'access';

    public function __construct($class, $property, $previous = null)
    {
        parent::__construct
        (
              'Cannot ' . static::ACCESS_TYPE . ' non existent '
            . 'property: ' . $class . '::$' . $property,
            0,
            $previous
        );
    }
}
