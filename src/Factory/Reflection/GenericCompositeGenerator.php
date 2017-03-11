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

namespace Spaark\CompositeUtils\Factory\Reflection;

use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Model\Reflection\ReflectionMethod;
use Spaark\CompositeUtils\Model\Reflection\Type\BooleanType;
use Spaark\CompositeUtils\Model\Reflection\Type\CollectionType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Model\Reflection\Type\MixedType;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\GenericType;
use Spaark\CompositeUtils\Model\Generic\GenericContext;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use Spaark\CompositeUtils\Service\GenericNameProvider;
use Spaark\CompositeUtils\Traits\AutoConstructTrait;

/**
 * Generates the code for a generic class
 */
class GenericCompositeGenerator
{
    use AutoConstructTrait;

    /**
     * @var ReflectionComposite
     * @construct required
     */
    protected $reflect;

    /**
     * @var GenericNameProvider
     */
    protected $nameProvider;

    /**
     * Creates an ObjectType from the given list of generics
     *
     * @param AbstractType[] $generics
     */
    private function createObject(...$generics) : ObjectType
    {
        $object = new ObjectType(get_class($this->reflect), '');
        $i = 0;

        foreach ($this->reflect->generics as $name => $value)
        {
            $object->generics[] = $generics[$i++] ?? $value;
        }

        return $object;
    }

    /**
     * Generate class code for the given generics
     *
     * @param AbstractType[] $generics
     * @return string
     */
    public function generateClassCode(...$generics) : string
    {
        $object = $this->createObject(...$generics);
        $this->nameProvider = new GenericNameProvider
        (
            new GenericContext($object, $this->reflect)
        );
        $class = $this->nameProvider->inferName($object);
        $originalClass = get_class($this->reflect);
        $i = 0;

        $code =
              '<?php namespace ' . $class->namespace . ';'
            . 'class ' . $class->classname . ' '
            .     'extends ' . $originalClass
            . '{';

        foreach ($this->reflect->methods as $method)
        {
            $code .= $this->generateMethodCode($method);
        }

        $code .= '}';

        return $code;
    }

    /**
     * Generates the method code for the current class being generated
     *
     * @param ReflectionMethod $method
     * @return string
     */
    public function generateMethodCode(ReflectionMethod $method)
        : string
    {
        $params = [];
        $newParams = [];
        $paramNames = [];
        foreach ($method->parameters as $i => $param)
        {
            $paramNames[] = $name = ' $' . $param->name;
            $params[] = $method->nativeParameters[$i] . $name;
            $newParams[] =
                $this->nameProvider->inferName($param->type) . $name;
        }

        return
              ($method->scope === 'static' ? 'static ' : '')
            . 'function ' . $method->name
            . '(' . implode(',', $params) . '){'
            . '__generic_' . $method->name
            . '(' . implode(',', $paramNames) . ');}'
            . "\n"
            . 'function __generic_' . $method->name
            . '(' . implode(',', $newParams) . '){'
            . 'parent::' . $method->name
            . '(' . implode(',', $paramNames) . ');}'
            . "\n";
    }
}
