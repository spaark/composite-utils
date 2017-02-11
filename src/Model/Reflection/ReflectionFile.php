<?php

namespace Spaark\CompositeUtils\Model\Reflection;

use Spaark\CompositeUtils\Model\Collection\Collection;

class ReflectionFile extends Reflector
{
    /**
     * @var NamespaceBlock[]
     * @readable
     */
    protected $namespaces;

    public function __construct()
    {
        $this->namespaces = new Collection();
        $this->initAllReadableTrait();
    }
}
