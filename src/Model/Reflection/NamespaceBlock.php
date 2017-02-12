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

use Spaark\CompositeUtils\Model\Collection\Collection;
use Spaark\CompositeUtils\Model\Collection\HashMap;

class NamespaceBlock extends Reflector
{
    /**
     * @var Collection
     * @readable
     */
    protected $definitions;

    /**
     * @var string
     * @readable
     */
    protected $namespace;

    /**
     * @var ReflectionFile
     * @readable
     */
    protected $file;

    /**
     * @var UseStatement[]
     * @readable
     */
    protected $useStatements;

    public function __construct(string $namespace)
    {
        $this->definitions = new Collection();
        $this->useStatements = new HashMap();
        $this->namespace = $namespace;
    }
}
