<?php namespace Spaark\Core\Model\Reflection;
/**
 * Spaark
 *
 * Copyright (C) 2012 Emily Shepherd
 * emily@emilyshepherd.me
 */


class Param
{
    public $cast;

    public $from;

    public $args;

    public $default;

    public $fromArg;

    public $optional = false;

    public function __construct($cast, $from = NULL, $args = 1)
    {
        if ($cast != 'string' && $cast != 'mixed')
        {
            $this->cast = $cast;
        }

        $this->from = $from;
        $this->args = $args;
    }
}

