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

use Spaark\CompositeUtils\Factory\BaseFactory;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Service\ReflectionCompositeProviderInterface;
use Spaark\CompositeUtils\Service\ReflectionCompositeProvider;
use \ReflectionClass as PHPNativeReflectionClass;
use \ReflectionProperty as PHPNativeReflectionProperty;
use \ReflectionMethod as PHPNativeReflectionMethod;
use \Reflector;

/**
 * Builds a ReflectionComposite for a given class
 */
class ReflectionCompositeFactory extends ReflectorFactory
{
    const REFLECTION_OBJECT = ReflectionComposite::class;

    /**
     * @var PHPNativeReflector
     */
    protected $reflector;

    /**
     * @var ReflectionComposite
     */
    protected $object;

    /**
     * @var ReflectionCompositeProviderInterface
     */
    protected $provider;

    /**
     * Creates a new ReflectionCompositeFactory from the given
     * classname
     *
     * @param string $classname The class to build a reflect upon
     * @return ReflectionCompositeFactory
     */
    public static function fromClassName(string $classname)
    {
        return new static
        (
            new PHPNativeReflectionClass($classname),
            ReflectionCompositeProvider::getDefault()
        );
    }

    public function __construct
    (
        PHPNativeReflectionClass $reflect,
        ReflectionCompositeProviderInterface $provider
    )
    {
        parent::__construct($reflect);
        $this->provider = $provider;
    }

    /**
     * Builds the ReflectionComposite from the provided parameters
     *
     * @return ReflectionComposite
     */
    public function build()
    {
        foreach ($this->reflector->getTraits() as $trait)
        {
            $this->addInheritance('traits', $trait);
        }

        if ($parent = $this->reflector->getParentClass())
        {
            $this->addInheritance('parent', $parent, 'setRawValue');
        }

        foreach ($this->reflector->getInterfaces() as $interface)
        {
            $this->addInheritance('interfaces', $interface);
        }

        $fileName = $this->reflector->getFileName();

        $file = (new ReflectionFileFactory($fileName))->build();
        $this->accessor->setRawValue('file', $file);

        $this->accessor->setRawValue
        (
            'classname',
            $this->reflector->name
        );
        $this->accessor->setRawValue
        (
            'namespace',
            $file->namespaces[$this->reflector->getNamespaceName()]
        );

        $this->addItems('properties', false, 'buildProperty');
        $this->addItems('methods', true, 'buildMethod');

        return $this->object;
    }

    protected function addItems
    (
        string $name,
        bool $checkFile,
        string $cb
    )
    {
        foreach ($this->reflector->{'get' . $name}() as $item)
        {
            if ($item->class === $this->reflector->getName())
            {
                $this->$cb($item);
            }
            // We only reflect on methods in userspace
            elseif (!$checkFile || $item->getFileName())
            {
                $this->accessor->getRawValue($name)[$item->getName()] =
                    $this->provider->get($item->class)
                        ->$name[$item->getName()];
            }
        }
    }

    protected function addInheritance
    (
        string $group,
        PHPNativeReflectionClass $reflect,
        string $method = 'rawAddToValue'
    )
    {
        // We only reflect on classes within userspace
        if ($reflect->getFileName())
        {
            $item = $this->provider->get($reflect->getName());
            $this->accessor->$method($group, $item);
        }
    }

    /**
     * Uses a ReflectionPropertyFactory to build a ReflectionProperty,
     * and adds that to this ReflectionComposite
     *
     * @param PHPNativeReflectionProperty
     */
    protected function buildProperty
    (
        PHPNativeReflectionProperty $reflect
    )
    {
        $property = (new ReflectionPropertyFactory($reflect))->build
        (
            $this->object,
            $this->reflector
                ->getDefaultProperties()[$reflect->getName()]
        );
        $this->accessor->getRawValue('properties')[$property->name] =
            $property;
        $this->accessor->rawAddToValue('localProperties', $property);
    }

    /**
     * Uses a ReflectionMethodFactory to build a ReflectionMethod, and
     * adds that to this ReflectionComposite
     *
     * @param PHPNativeReflectionMethod
     */
    protected function buildMethod(PHPNativeReflectionMethod $reflect)
    {
        $methods = $this->accessor->getRawValue('methods');
        $methods[$reflect->getName()] =
            (new ReflectionMethodFactory($reflect))
                ->build($this->object);
    }
}

