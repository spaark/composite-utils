<?php

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
