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
use Spaark\CompositeUtils\Model\Collection\ArrayList;


/**
 * Represents a composite class
 *
 * @property-read HashMap $properties
 * @property-read ArrayList $requiredProperties
 * @property-read ArrayList $optionalProperties
 * @property-read ArrayList $builtProperties
 * @property-read HashMap $methods
 * @property-read ReflectionFile $file
 * @property-read NamespaceBlock $namespace
 * @property-read string $classname
 */
class ReflectionComposite extends Reflector
{
    /**
     * @var ?ReflectionComposite
     */
    protected $parent;

    /**
     * @var ArrayList
     */
    protected $traits;

    /**
     * @var ArrayList
     */
    protected $interfaces;

    /**
     * The properties within the composite
     *
     * @var HashMap
     */
    protected $properties;

    /**
     * Properties local to this composite
     *
     * @var ArrayList
     */
    protected $localProperties;

    /**
     * The properties which are required in the composite's constructor
     *
     * @var ArrayList
     */
    protected $requiredProperties;

    /**
     * The properties which can be optionally passed to the composite's
     * constructor
     *
     * @var ArrayList
     */
    protected $optionalProperties;

    /**
     * The properties which will be built without input in the
     * composite's constructor
     *
     * @var ArrayList
     */
    protected $builtProperties;

    /**
     * The methods within the composite
     *
     * @var HashMap
     */
    protected $methods;

    /**
     * Method local to this composite
     *
     * @var ArrayList
     */
    protected $localMethods;

    /**
     * The file in which this composite was declared
     *
     * @var ReflectionFile
     */
    protected $file;

    /**
     * The namespace this composite was declared inside
     *
     * @var NamespaceBlock
     */
    protected $namespace;

    /**
     * The name of this composite class
     *
     * @var string
     */
    protected $classname;

    /**
     * Creates the ReflectionComposite by initializing its ArrayList
     * properties
     *
     * As the ReflectionComposite use a requirement of the AutoConstruct
     * feature, this class is not able to make use of it (as it would
     * create an unresolvable circular dependancy)
     */
    public function __construct()
    {
        $this->traits = new ArrayList();
        $this->interfaces = new ArrayList();
        $this->properties = new HashMap();
        $this->localProperties = new ArrayList();
        $this->methods = new HashMap();
        $this->localMethods = new ArrayList();
        $this->requiredProperties = new ArrayList();
        $this->optionalProperties = new ArrayList();
        $this->builtProperties = new ArrayList();
    }
}
