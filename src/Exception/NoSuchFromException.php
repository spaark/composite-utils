<?php
/**
 * Spaark
 *
 * Copyright (C) 2012 Emily Shepherd
 * emily@emilyshepherd.me
 */

namespace Spaark\Core\Exception;


/**
 * Thrown when a non-existant static method is called, that begins with
 * "from"
 */
class NoSuchFromException extends NoSuchMethodException
{
    private $obj;

    public function __construct($method, $obj)
    {
        parent::__construct($obj, $method);

        $this->obj = $obj;
    }

    public function getObj()
    {
        return $this->obj;
    }
}
