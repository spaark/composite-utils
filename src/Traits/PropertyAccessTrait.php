<?php

namespace Spaark\Composite\Traits;

use Spaark\CompositeUtils\Service\ConditionalPropertyAccessor;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;

trait PropertyAccessTrait
{
    protected static $reflectionComposite;

    protected static function getReflectionComposite()
    {
        if (!static::$reflectionComposite)
        {
            static::$reflectComposite =
                ReflectionCompositeFactory::fromClassName
                (
                    get_called_class()
                )
                ->build();
        }

        return static::$reflectComposite;
    }

    /**
     * @var ConditionalPropertyAccessor
     */
    protected $accessor;

    public function __construct()
    {
        $this->initPropertyAccessTrait();
    }

    protected function initPropertyAccessTrait()
    {
        $this->accessor = new ConditionalPropertyAccessor
        (
            $this,
            self::getReflectionComposite()
        );
    }

    public function __get($property)
    {
        return $this->accessor->getValue($property);
    }

    public function __set($property, $value)
    {
        $this->accessor->setValue($property, $value);
    }
}
