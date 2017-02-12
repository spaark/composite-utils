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
 * @author Emily Shepherd <emily@emilyshepherd>
 * @license MIT
 */

namespace Spaark\CompositeUtils\Traits;

use Spaark\CompositeUtils\Service\ConditionalPropertyAccessor;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;

trait PropertyAccessTrait
{
    protected static $reflectionComposite;

    protected static function getReflectionComposite()
    {
        if (!static::$reflectionComposite)
        {
            static::$reflectionComposite =
                ReflectionCompositeFactory::fromClassName
                (
                    get_called_class()
                )
                ->build();
        }

        return static::$reflectionComposite;
    }

    /**
     * @var ConditionalPropertyAccessor
     */
    protected $accessor;

    protected function getPropertyAccessor()
    {
        if (!$this->accessor)
        {
            $this->accessor = new ConditionalPropertyAccessor
            (
                $this,
                self::getReflectionComposite()
            );
        }

        return $this->accessor;
    }

    public function __get($property)
    {
        return $this->getPropertyAccessor()->getValue($property);
    }

    public function __set($property, $value)
    {
        $this->getPropertyAccessor()->setValue($property, $value);
    }
}
