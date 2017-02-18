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

namespace Spaark\CompositeUtils\Service;

use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;
use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;
use Spaark\CompositeUtils\Model\Collection\HashMap;

/**
 */
class ReflectionCompositeProvider
    implements ReflectionCompositeProviderInterface
{
    /**
     * @var ReflectionCompositeProviderInterface
     */
    protected static $default;

    public static function getDefault()
        : ReflectionCompositeProviderInterface
    {
        if (!static::$default)
        {
            static::$default = new static();
        }

        return static::$default;
    }

    public static function setDefault
    (
        ReflectionCompositeProviderInterface $default
    )
    {
        static::$default = $default;
    }

    private $cache;

    public function __construct()
    {
        $this->cache = new HashMap();
    }

    public function get(string $classname) : ReflectionComposite
    {
        if (!$this->cache->containsKey($classname))
        {
            $this->cache[$classname] =
                (
                    ReflectionCompositeFactory::fromClassName
                    (
                        $classname
                    )
                )
                ->build();
        }
        
        return $this->cache[$classname];
    }
}
