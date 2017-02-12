<?php

namespace Spaark\CompositeUtils\Traits;

use Spaark\CompositeUtils\Service\RawPropertyAccessor;

trait AllReadableTrait
{
    /**
     * @var RawPropertyAccessor
     */
    protected $accessor;

    public function __construct()
    {
        $this->initAllReadableTrait();
    }

    protected function initAllReadableTrait()
    {
        $this->accessor = new RawPropertyAccessor($this);
    }

    public function __get($property)
    {
        return $this->accessor->getRawValue($property);
    }
}
