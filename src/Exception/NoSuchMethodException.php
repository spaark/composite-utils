<?php

namespace Spaark\Core\Exception;

class NoSuchMethodException extends \Exception
{
    public function __construct($class, $method)
    {
        if (is_object($class))
        {
            $class = get_class($class);
        }

        parent::__construct
        (
            'Method "' . $method . '" does not exist for ' . $class
        );
    }
}

