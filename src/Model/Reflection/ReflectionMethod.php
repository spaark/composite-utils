<?php
/**
 * This file is part of the Composite Utils package.
 *
 * (c) Emily Shepherd <emily@emilyshepherd.me>
 *
 * For the full copyright and licence information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package spaark/composite-utils
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Model\Reflection;
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

