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

/**
 * Parses a type string, optionally using a context to lookup generic
 * and use referenced names
 */
class TypeParser
{
    protected $context;

    public function __construct(ReflectionComposite $context = null)
    {
        $this->context = $context;
    }

    /**
     * Sets the property's type by parsing the @type annotation
     *
     * @param string $value The value string to parse
     * @return AbstractType The type of this item
     */
    public function parse($value)
    {
        return $this->innerParse($value);
    }

    protected function innerParse($value)
    {
        $nullable = false;
        $collection = false;
        $stack = new \SplStack();
        $current = '';

        for ($i = 0; $i < strlen($value); $i++)
        {
            $char = $value{$i};
            switch ($char)
            {
                case '?':
                    $nullable = true;
                    break;
                case '<':
                    $stack->push($this->resolveGenericName
                    (
                        $current,
                        $nullable,
                        $collection
                    ));
                    break;
                case ',':
                    $stack->top()->generics[] =
                        $this->resolveName
                        (
                            $current,
                            $nullable,
                            $collection
                        );
                    break;
                case '[':
                    $collection = true;
                    $this->checkCollectionClose($value, $i);
                    $i++;
                    break;
                case ' ':
                    break;
                case '>':
                    $item = $stack->pop();
                    if ($value{$i - 1} !== '>')
                    {
                        $item->generics[] =
                            $this->resolveName
                            (
                                $current,
                                $nullable,
                                $collection
                            );
                    }

                    if ($i + 1 !== strlen($value) && $value{$i + 1} === '[')
                    {
                        $this->checkCollectionClose($value, $i + 1);
                        $item = new CollectionType($item);
                    }


                    if ($stack->isEmpty())
                    {
                        return $item;
                    }
                    else
                    {
                        $stack->top()->generics[] = $item;
                    }
                    break;
                default:
                    $current .= $char;
            }
        }

        return $this->resolveName($current, $nullable, $collection);
    }

    protected function checkCollectionClose($value, $i)
    {
        if ($i + 1 === strlen($value))
        {
            throw new \Exception('Unexpected EOF');
        }
        elseif ($value{$i + 1} !== ']')
        {
            throw new \Exception('[ must be followed by ]');
        }
        elseif ($i + 2 !== strlen($value))
        {
            if (!in_array($value{$i + 2}, ['>',',']))
            {
                throw new \Exception('Unexpected char after collection');
            }
        }
    }

    protected function resolveGenericName
    (
        string &$value,
        bool &$nullable,
        bool &$collection
    )
    {
        $type = $this->resolveName($value, $nullable, $collection);

        if (!$type instanceof ObjectType)
        {
            throw \Exception();
        }

        return $type;
    }

    protected function resolveName
    (
        string &$value,
        bool &$nullable,
        bool &$collection
    )
    {
        switch ($value)
        {
            case 'string':
                $class = new StringType();
                break;
            case 'int':
            case 'integer':
                $class = new IntegerType();
                break;
            case 'bool':
            case 'boolean':
                $class = new BooleanType();
                break;
            case 'mixed':
            case '':
                $class = new MixedType();
                break;
            default:
                if ($this->context)
                {
                    $useStatements =
                        $this->context->namespace->useStatements;
                    $generics = $this->context->generics;

                    if ($useStatements->containsKey($value))
                    {
                        $value = $useStatements[$value]->classname;
                    }
                    elseif ($generics->containsKey($value))
                    {
                        $value = new GenericType($value);
                    }
                    else
                    {
                        $value = $this->context->namespace->namespace
                            . '\\' . $value;
                    }
                }

                $class = new ObjectType($value);
        }

        if ($nullable)
        {
            (new RawPropertyAccessor($class))
                ->setRawValue('nullable', true);
        }

        if ($collection)
        {
            $class = new CollectionType($class);
        }

        $value = '';
        $nullable = false;
        $collection = false;

        return $class;
    }
}
