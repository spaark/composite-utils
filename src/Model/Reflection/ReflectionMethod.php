<?php namespace Spaark\Core\Model\Reflection;
/**
 * Spaark
 *
 * Copyright (C) 2012 Emily Shepherd
 * emily@emilyshepherd.me
 */


class ReflectionMethod extends Reflector
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Model
     */
    protected $owner;

    /**
     * @var HashMap
     */
    protected $parameters;

    /**
     * @var Visibility
     */
    protected $visbility;

    /**
     * @var Scope
     */
    protected $scope;

    /**
     * @var boolean
     */
    protected $final;
}

