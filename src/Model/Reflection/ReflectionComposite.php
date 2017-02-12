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
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Model\Reflection;

use Spaark\CompositeUtils\Model\Collection\HashMap;
use Spaark\CompositeUtils\Model\Collection\Collection;


/**
 * Reflects upon model classes and deals with their getter methods and
 * properties
 */
class ReflectionComposite extends Reflector
{
    /**
     * @var HashMap
     */
    protected $properties;

    /**
     * @var Collection
     */
    protected $requiredProperties;

    /**
     * @var Collection
     */
    protected $optionalProperties;

    /**
     * @var Collection
     */
    protected $builtProperties;

    /**
     * @var HashMap
     */
    protected $methods;

    /**
     * @var ReflectionFile
     */
    protected $file;

    /**
     * @var NamespaceBlock
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $classname;

    public function __construct()
    {
        $this->properties = new HashMap();
        $this->methods = new HashMap();
        $this->requiredProperties = new Collection();
        $this->optionalProperties = new Collection();
        $this->builtProperties = new Collection();
    }
}
