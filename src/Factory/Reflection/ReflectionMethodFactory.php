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
use Spaark\CompositeUtils\Model\Reflection\ReflectionParameter;
use Spaark\CompositeUtils\Service\RawPropertyAccessor;
use Spaark\CompositeUtils\Service\TypeComparator;
use Spaark\CompositeUtils\Model\Collection\ListCollection\FixedList;
use Spaark\CompositeUtils\Model\Collection\Map\HashMap;
use \ReflectionMethod as PHPNativeReflectionMethod;
use \ReflectionParameter as PHPNativeReflectionParameter;
use \Reflector as PHPNativeReflector;

/**
 * Builds a ReflectionMethod for a given method and optionally links
 * this to a parent ReflectionComposite
 */
class ReflectionMethodFactory extends ReflectorFactory
{
    const REFLECTION_OBJECT = ReflectionMethod::class;

    /**
     * @var PHPNativeReflectionMethod
     */
    protected $reflector;

    /**
     * @var ReflectionMethod
     */
    protected $object;

    /**
     * @var Hashmap
     */
    protected $parameters;

    /**
     * @var TypeParser
     */
    protected $typeParser;

    /**
     * Returns a new ReflectionMethodFactory using the given class and
     * method names
     *
     * @param string $class The classname of the method
     * @param string $method The method to reflect
     * @return ReflectionMethodFactory
     */
    public static function fromName(string $class, string $method)
    {
        return new static(new PHPNativeReflectionMethod
        (
            $class, $method
        ));
    }

    public function __construct(PHPNativeReflector $reflector)
    {
        parent::__construct($reflector);

        $this->parameters = new HashMap();
    }

    /**
     * Builds the ReflectionMethod from the provided parameters,
     * optionally linking to a parent ReflectionComposite
     *
     * @param ReflectionComposite $parent The reflector for the class
     *     this method belongs to
     * @return ReflectionMethod
     */
    public function build(?ReflectionComposite $parent = null)
    {
        $this->typeParser = new TypeParser($parent);
        $this->accessor->setRawValue('owner', $parent);
        $this->accessor->setRawValue
        (
            'name',
            $this->reflector->getName()
        );

        $this->initParams();

        $this->accessor->setRawValue('visibility',
              ($this->reflector->isPublic() ? 'public'
            : ($this->reflector->isProtected() ? 'protected'
            : ($this->reflector->isPrivate() ? 'private'
            : (''))))
        );
        $this->accessor->setRawValue('scope',
            ($this->reflector->isStatic() ? 'static' : 'dynamic')
        );
        $this->accessor->setRawValue('final',
            $this->reflector->isFinal()
        );

        foreach ($this->reflector->getParameters() as $parameter)
        {
            $this->addParameter($parameter);
        }

        $this->parseDocComment(['param' => 'addParamAnnotation']);

        return $this->object;
    }

    /**
     * Creates the Method's parameter's property with a fixd list of
     * the appropriate size
     */
    protected function initParams()
    {
        $this->accessor->setRawValue
        (
            'parameters',
            new FixedList(count($this->reflector->getParameters()))
        );
        $this->accessor->setRawValue
        (
            'nativeParameters',
            new FixedList(count($this->reflector->getParameters()))
        );
    }

    /**
     * Processes a param docblock annotation and uses it to decorate
     * a method parameter
     *
     * @param string $name Unused. Should be 'param'
     * @param string $value The annotation value
     */
    protected function addParamAnnotation($name, $value) : void
    {
        $items = explode(' ', $value);
        $type = $items[0];
        $param = $items[1];

        if (!$this->parameters->containsKey($param))
        {
            throw new \Exception
            (
                  'Tried to set param annotation for non existant '
                . 'parameter: ' . $param
            );
        }

        $comparator = new TypeComparator();
        $type = $this->typeParser->parse($type);
        $param = $this->parameters[$param];
        $nativeType = $param->getRawValue('type');

        if (!$comparator->compatible($nativeType, $type))
        {
            throw new \Exception
            (
                  'Types are incompatible for: '
                . $this->reflector->getName() . '::' . $items[1]
            );
        }

        $param->setRawValue('type', $type);
    }

    /**
     * Adds a parameter to the method, based on it's native
     * ReflectionParameter
     *
     * @param PHPNativeReflectionParameter $reflect
     */
    protected function addParameter
    (
        PHPNativeReflectionParameter $reflect
    )
    : void
    {
        $parameter = new ReflectionParameter();
        $accessor = new RawPropertyAccessor($parameter);
        $type = (new TypeParser())->parse((string)$reflect->getType());

        $this->parameters['$' . $reflect->getName()] = $accessor;
        $this->accessor->rawAddToValue('parameters', $parameter);
        $this->accessor->rawAddToValue('nativeParameters',
            (string)$reflect->getType()
        );

        $accessor->setRawValue('owner', $this->object);
        $accessor->setRawValue('name', $reflect->getName());
        $accessor->setRawValue('type', $type);
    }
}

