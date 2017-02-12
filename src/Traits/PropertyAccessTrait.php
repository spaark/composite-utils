<?php

namespace Spaark\Composite\Traits;

use Spaark\CompositeUtils\Service\ConditionalPropertyAccessor;

trait PropertyAccessTrait
{
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
            ReflectionComposite::fromClassName(get_class($this))
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
