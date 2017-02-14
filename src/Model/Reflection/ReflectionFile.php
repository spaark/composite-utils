<?php
/**
 * This file is part of the Composite Utils package.
 *
 * (c) Emily Shepherd <emily@emilyshepherd.me>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package spaark/composite-utils
 * @author Emily Shepherd <emily@emilyshepherd.me>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Model\Reflection;

use Spaark\CompositeUtils\Model\Collection\HashMap;

/**
 * Represents a file
 *
 * @property-read HashMap $namespaces
 */
class ReflectionFile extends Reflector
{
    /**
     * The namespaces which are declared within this file
     *
     * Normally, and with well formatted code, there should only really
     * ever be one of these
     *
     * @var HashMap
     * @readable
     */
    protected $namespaces;

    /**
     * Creates the ReflectionFile by initializing its HashMap property
     */
    public function __construct()
    {
        $this->namespaces = new HashMap();
    }
}
