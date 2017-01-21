<?php
/**
 * Spaark
 *
 * Copyright (C) 2012 Emily Shepherd
 * emily@emilyshepherd.me
 */

namespace Spaark\Core\Exception;

/**
 * Thrown when a model cannot be created
 *
 * This may happen when an invalid / non-existant id is given. Eg:
 *   User::fromEmail('not-real@example.com');
 *
 * That would normally return an instance of User, but as that email
 * address doesn't exist, an error is thrown
 */
class CannotCreateModelException extends \Exception
{
    private $obj;

    public function __construct($model, $from, $val)
    {
        $modelName = is_object($model) ? get_class($model) : $model;
        $val       = is_array($val)    ? implode($val)     : $val;

        parent::__construct
        (
              'Failed to create ' . $modelName . ' from '
            . $from . ': ' . $val
        );

        $this->obj = $model;
    }

    public function getObj()
    {
        return $this->obj;
    }
}
