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

use Spaark\CompositeUtils\Model\Collection\ListCollection\FlexibleList;
use Spaark\CompositeUtils\Model\Collection\Map\HashMap;

/**
 * Represents a namespace declaration within a file
 *
 * @property-read FlexibleList $definitions
 * @property-read string $namespace
 * @property-read ReflectionFile $file
 * @property-read HashMap $useStatements
 */
class NamespaceBlock extends Reflector
{
    /**
     * Currently unused
     *
     * @var FlexibleList
     * @readable
     */
    protected $definitions;

    /**
     * The name of this namespace
     *
     * @var string
     * @readable
     */
    protected $namespace;

    /**
     * The file this namespace is declared in
     *
     * @var ReflectionFile
     * @readable
     */
    protected $file;

    /**
     * Set of use statements in this namespace block
     *
     * @var HashMap
     * @readable
     */
    protected $useStatements;

    /**
     * Creates a new NamespaceBlock with the given name
     *
     * @param string $namespace The name of the namespace
     */
    public function __construct(string $namespace)
    {
        $this->definitions = new FlexibleList();
        $this->useStatements = new HashMap();
        $this->namespace = $namespace;
    }
}
