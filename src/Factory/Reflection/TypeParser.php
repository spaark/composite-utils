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
use Spaark\CompositeUtils\Model\Reflection\Type\AbstractType;
use Spaark\CompositeUtils\Model\Reflection\Type\FloatType;
use Spaark\CompositeUtils\Model\Reflection\Type\NullType;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;

/**
 * Parses a type string
 */
class TypeParser
{
    /**
     * @var ReflectionComposite
     */
    protected $context;

    /**
     * @var boolean
     */
    protected $nullable;

    /**
     * @var boolean
     */
    protected $collection;

    /**
     * @var string
     */
    protected $currentValue;

    /**
     * Constructs the TypeParser with an optional context for
     * interpreting classnames
     *
     * @param ReflectionComposite $context
     */
    public function __construct(ReflectionComposite $context = null)
    {
        $this->context = $context;
    }

    /**
     * Accepts any value and returns its type
     *
     * @param mixed $var
     * @return AbstractType
     */
    public function parseFromType($var) : AbstractType
    {
        if (is_object($var))
        {
            return $this->parseObjectName($var);
        }
        
        return $this->scalarToType(gettype($var));
    }

    /**
     * Accepts an object and returns its type
     *
     * @param mixed $var
     * @return ObjectType
     */
    public function parseObjectName($var) : ObjectType
    {
        return $this->parse(get_class($var));
    }

    /**
     * Sets the property's type by parsing the @type annotation
     *
     * @param string $value The value string to parse
     * @return AbstractType The type of this item
     */
    public function parse(string $value) : AbstractType
    {
        $this->nullable = false;
        $this->collection = false;
        $stack = new \SplStack();
        $this->currentValue = '';

        for ($i = 0; $i < strlen($value); $i++)
        {
            $char = $value{$i};
            switch ($char)
            {
                case '?':
                    $this->nullable = true;
                    break;
                case '[':
                    $this->collection = true;
                    $this->checkCollectionClose($value, $i);
                    $i++;
                    break;
                case ' ':
                    break;
                default:
                    $this->currentValue .= $char;
            }
        }

        return $this->resolveName();
    }

    /**
     * Checks that the given value at the given offset closes a
     * collection block correctly
     *
     * @param string $value
     * @param int $i
     * @return void
     */
    protected function checkCollectionClose(string $value, int $i)
        : void
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

    /**
     * Interprets the currentValue and converts it to an AbstractType
     *
     * @param AbstractType
     */
    protected function currentValueToType() : AbstractType
    {
        if ($var = $this->scalarToType($this->currentValue))
        {
            return $var;
        }

        $context = $this->checkContext();
        return new ObjectType($context);
    }

    public function scalarToType($var) : ?AbstractType
    {
        switch (strtolower($var))
        {
            case 'string':
                return new StringType();
            case 'int':
            case 'integer':
                return new IntegerType();
            case 'bool':
            case 'boolean':
                return new BooleanType();
            case 'float':
            case 'double':
                return new FloatType();
            case 'mixed':
            case '':
                return new MixedType();
            case 'null':
                return new NullType();
            default:
                return null;
        }
    }

    /**
     * Resolves the currentValue to an AbstractType, setting it up as
     * nullable or a collection as appropriate
     *
     * @return AbstractType
     */
    protected function resolveName() : AbstractType
    {
        $class = $this->currentValueToType();

        if ($this->nullable)
        {
            (new RawPropertyAccessor($class))
                ->setRawValue('nullable', true);
        }

        if ($this->collection)
        {
            $class = new CollectionType($class);
        }

        $this->currentValue = '';
        $this->nullable = false;
        $this->collection = false;

        return $class;
    }

    /**
     * Checks if the currentValue means something in the TypeParser's
     * context
     *
     * @return string The fully resolved classname
     */
    protected function checkContext()
    {
        if (!$this->context)
        {
            return $this->currentValue;
        }

        $useStatements = $this->context->namespace->useStatements;

        if ($useStatements->containsKey($this->currentValue))
        {
            return $useStatements[$this->currentValue]->classname;
        }
        else
        {
            return $this->context->namespace->namespace
                . '\\' . $this->currentValue;
        }
    }
}
