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
use Spaark\CompositeUtils\Model\Reflection\Type\BooleanType;
use Spaark\CompositeUtils\Model\Reflection\Type\CollectionType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Model\Reflection\Type\MixedType;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Model\Reflection\Type\GenericType;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use Spaark\CompositeUtils\Service\GenericNameProvider;

/**
 */
class GenericCompositeGenerator
{
    protected $reflect;

    protected $nameProvider;

    public function __construct(ReflectionComposite $reflect)
    {
        $this->reflect = $reflect;
    }

    private function createObject(...$generics)
    {
        $object = new ObjectType(get_class($this->reflect), '');
        $i = 0;

        foreach ($this->reflect->generics as $name => $value)
        {
            $object->generics[] = $generics[$i++] ?? $value;
        }

        return $object;
    }

    public function generateClassCode(...$generics)
    {
        $this->nameProvider = new GenericNameProvider();
        $object = $this->createObject(...$generics);
        $class = $this->nameProvider->inferName($object);

        $class = explode('\\', $class);
        $baseClass = $class[count($class) - 1];
        unset($class[count($class) - 1]);
        $namespace = implode('\\', $class);
        $originalClass = get_class($this->reflect);
        $i = 0;

        $code =
              '<?php namespace ' . $namespace . ';'
            . 'class ' . $baseClass . ' extends ' . $originalClass
            . '{';

        foreach ($this->reflect->methods as $method)
        {
            $code .= $this->generateMethodCode($method, $object);
        }

        $code .= '}';

        return $code;
    }

    public function generateMethodCode($method, ObjectType $object)
    {
        $params = [];
        $newParams = [];
        $paramNames = [];
        foreach ($method->parameters as $i => $param)
        {
            if (!$param->type instanceof GenericType)
            {
                $type = $param->type;
            }
            else
            {
                $index = $this->reflect->generics->indexOfKey
                (
                    $param->type->name
                );

                $type = $object->generics[$index];
            }

            $paramNames[] = $name = '$' . $param->name;
            $params[] = $method->nativeParameters[$i] . ' ' . $name;
            $newParams[] =
                $this->nameProvider->inferName($type) . ' ' . $name;
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
