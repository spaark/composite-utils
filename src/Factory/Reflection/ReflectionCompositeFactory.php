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
use Spaark\CompositeUtils\Model\Reflection\ReflectionProperty;
use Spaark\CompositeUtils\Model\Reflection\ReflectionMethod;
use Spaark\CompositeUtils\Model\Collection\ListCollection\FixedList;
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

    const PROPERTIES = [
        'traits' => [''],
        'interfaces' => [''],
        'Methods' => ['local'],
        'Properties' => ['local', 'required', 'optional', 'built']
    ];

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

    /**
     * Constructs the Factory with the given reflector and Composite
     * provider
     *
     * @param PHPNativeReflectionClass $reflect
     * @param ReflectionCompositeProviderInterface $provider
     */
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
        $this->initObject();

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

        $this->addItems('properties', false, 'Property');
        $this->addItems('methods', true, 'Method');

        $this->resizeProperties();

        return $this->object;
    }

    /**
     * Initialise the object with fixed lists
     */
    protected function initObject()
    {
        foreach (static::PROPERTIES as $name => $prefixes)
        {
            $size = count($this->reflector->{'get' . $name}());
            foreach ($prefixes as $prefix)
            {
                $this->accessor->setRawValue
                (
                    $prefix . $name,
                    new FixedList($size)
                );
            }
        }
    }

    /**
     * Resize the FixedList properties down to their size
     */
    protected function resizeProperties()
    {
        foreach (static::PROPERTIES as $name => $prefixes)
        {
            foreach ($prefixes as $prefix)
            {
                $this->object->{$prefix . $name}->resizeToFull();
            }
        }
    }

    /**
     * Loops through the list methods or properties adding them to the
     * Composite
     *
     * @param string $name
     * @param bool $checkFile
     * @param string $singular
     */
    protected function addItems
    (
        string $name,
        bool $checkFile,
        string $signular
    )
    {
        foreach ($this->reflector->{'get' . $name}() as $item)
        {
            // We only reflect on methods in userspace
            if ($checkFile && !$item->getFileName())
            {
                continue;
            }
            // This belongs to a super class, use that definition
            // instead
            elseif ($item->class !== $this->reflector->getName())
            {
                $item = $this->provider->get($item->class)
                    ->$name[$item->getName()];
            }
            // Parse this method
            else
            {
                $factory =
                      '\Spaark\CompositeUtils\Factory\Reflection'
                    . '\Reflection' . $signular . 'Factory';
                $item = $this->{'build' . $signular}
                (
                    new $factory($item),
                    $item
                );
                $this->accessor->rawAddToValue
                (
                    'local' . ucfirst($name),
                    $item
                );
            }

            $this->accessor->getRawValue($name)[$item->name] = $item;
        }
    }

    /**
     * Adds a super class / interface / trait to this Composite
     *
     * @param string $group The type of superclass (parent, etc...)
     * @param PHPNativeReflectionClass $reflect
     * @param string $method
     */
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
     * Uses a ReflectionPropertyFactory to build a ReflectionProperty
     *
     * @param ReflectionPropertyFactory $factory
     * @return ReflectionProperty
     */
    protected function buildProperty
    (
        ReflectionPropertyFactory $factory,
        PHPNativeReflectionProperty $reflect
    )
    : ReflectionProperty
    {
        return $factory->build
        (
            $this->object,
            $this->reflector
                ->getDefaultProperties()[$reflect->getName()]
        );
    }

    /**
     * Uses a ReflectionMethodFactory to build a ReflectionMethod
     *
     * @param ReflectionMethodFactory $factory
     * @return ReflectionMethod
     */
    protected function buildMethod(ReflectionMethodFactory $factory)
        : ReflectionMethod
    {
        return $factory->build($this->object);
    }
}

