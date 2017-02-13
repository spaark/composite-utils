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
 * Represents a composite class
 *
 * @property-read HashMap $properties
 * @property-read Collection $requiredProperties
 * @property-read Collection $optionalProperties
 * @property-read Collection $builtProperties
 * @property-read HashMap $methods
 * @property-read ReflectionFile $file
 * @property-read NamespaceBlock $namespace
 * @property-read string $classname
 */
class ReflectionComposite extends Reflector
{
    /**
     * The properties within the composite
     *
     * @var HashMap
     */
    protected $properties;

    /**
     * The properties which are required in the composite's constructor
     *
     * @var Collection
     */
    protected $requiredProperties;

    /**
     * The properties which can be optionally passed to the composite's
     * constructor
     *
     * @var Collection
     */
    protected $optionalProperties;

    /**
     * The properties which will be built without input in the
     * composite's constructor
     *
     * @var Collection
     */
    protected $builtProperties;

    /**
     * The methods within the composite
     *
     * @var HashMap
     */
    protected $methods;

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
     * Creates the ReflectionComposite by initializing its Collection
     * properties
     */
    public function __construct()
    {
        $this->properties = new HashMap();
        $this->methods = new HashMap();
        $this->requiredProperties = new Collection();
        $this->optionalProperties = new Collection();
        $this->builtProperties = new Collection();
    }
}
