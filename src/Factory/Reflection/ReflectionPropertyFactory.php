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
use Spaark\CompositeUtils\Model\Reflection\ReflectionProperty;
use Spaark\CompositeUtils\Model\Reflection\ReflectionParameter;
use Spaark\CompositeUtils\Model\Reflection\Type\BooleanType;
use Spaark\CompositeUtils\Model\Reflection\Type\CollectionType;
use Spaark\CompositeUtils\Model\Reflection\Type\IntegerType;
use Spaark\CompositeUtils\Model\Reflection\Type\MixedType;
use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Reflection\Type\StringType;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use \ReflectionProperty as PHPNativeReflectionProperty;

/**
 * Builds a ReflectionProperty for a given class and property name
 */
class ReflectionPropertyFactory extends ReflectorFactory
{
    const REFLECTION_OBJECT = ReflectionProperty::class;

    /**
     * @var PHPNativeReflectionProperty
     */
    protected $reflector;

    /**
     * @var ReflectionProperty
     */
    protected $object;

    /**
     * {@inheritDoc}
     */
    protected $acceptedParams =
    [
        'readable' => 'setBool',
        'writable' => 'setBool',
        'var' => 'setType',
        'construct' => 'setConstruct'
    ];

    /**
     * Returns a new ReflectionPropertyFactory using the given class and
     * property names
     *
     * @param string $class The classname of the property
     * @param string $property The property to reflect
     * @return ReflectionPropertyFactory
     */
    public static function fromName($class, $property)
    {
        return new static(new PHPNativeReflectionProperty
        (
            $class, $property
        ));
    }

    /**
     * Builds the ReflectionProperty from the provided parameters,
     * linking to a parent ReflectionComposite
     *
     * @param ReflectionCompostite $parent The reflector for the class
     *     this property belongs to
     * @param mixed $default This property's default value
     * @return ReflectionProperty
     */
    public function build(ReflectionComposite $parent, $default)
    {
        $this->accessor->setRawValue('owner', $parent);
        $this->accessor->setRawValue('defaultValue', $default);
        $this->accessor->setRawValue
        (
            'name',
            $this->reflector->getName()
        );

        $this->parseDocComment();

        return $this->object;
    }

    /**
     * Sets the property's type by parsing the @type annotation
     *
     * @param string $name Should be 'var'
     * @param string $value The value of the annotation
     */
    protected function setType($name, $value)
    {
        if ($value{0} !== '?')
        {
            $nullable = false;
        }
        else
        {
            $nullable = true;
            $value = substr($value, 1);
        }

        if (substr($value, -2) !== '[]')
        {
            $collection = false;
        }
        else
        {
            $collection = true;
            $value = substr($value, 0, -2);
        }

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
            case 'null':
                $class = new NullType();
                break;
            default:
                $useStatements =
                    $this->object->owner->namespace->useStatements;

                if ($useStatements->contains($value))
                {
                    $value = $useStatements[$value]->classname;
                }
                else
                {
                    $value = $this->object->owner->namespace->namespace
                        . '\\' . $value;
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

        $this->accessor->setRawValue('type', $class);
    }

    /**
     * Sets the property's constructor options by parsing the @construct
     * annotation
     *
     * @param string $name Should be 'construct'
     * @param string $value The value of the annotation
     */
    protected function setConstruct($name, $value)
    {
        $value = explode(' ', $value);
        $compositeAccessor =
            new RawPropertyAccessor($this->object->owner);

        switch ($value[0])
        {
            case 'required':
                $this->accessor->setRawValue
                (
                    'passedToConstructor',
                    true
                );
                $this->accessor->setRawValue
                (
                    'requiredInConstructor',
                    true
                );
                $compositeAccessor->rawAddToValue
                (
                    'requiredProperties',
                    $this->object
                );
                break;
            case 'new':
                $this->accessor->setRawValue
                (
                    'builtInConstructor',
                    true
                );
                $compositeAccessor->rawAddToValue
                (
                    'builtProperties',
                    $this->object
                );
                break;
            case 'optional':
                $this->accessor->setRawValue
                (
                    'passedToConstructor',
                    true
                );
                $compositeAccessor->rawAddToValue
                (
                    'optionalProperties',
                    $this->object
                );

                if (isset($value[1]) && $value[1] === 'new')
                {
                    $this->accessor->setRawValue
                    (
                        'builtInConstructor',
                        true
                    );
                }
        }
    }
}

