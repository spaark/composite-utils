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

    /**
     * Returns the default ReflectionCompositeProviderInteface used by
     * the application
     *
     * If one has not been set, a new ReflectionCompositeProvider is
     * instanciated on the fly
     *
     * @return ReflectionCompositeProviderInterface
     */
    public static function getDefault()
        : ReflectionCompositeProviderInterface
    {
        if (!static::$default)
        {
            static::$default = new static();
        }

        return static::$default;
    }

    /**
     * Sets the default ReflectionCompositeProviderInterface used by
     * the applciation
     *
     * @param ReflectionCompositeProviderInterface $default
     */
    public static function setDefault
    (
        ReflectionCompositeProviderInterface $default
    )
    {
        static::$default = $default;
    }

    /**
     * Cache used by this provider
     *
     * @var HashMap
     */
    private $cache;

    /**
     * Builds the provider by instanciating its cache
     */
    public function __construct()
    {
        $this->cache = new HashMap();
    }

    /**
     * Gets a ReflectionComposite, either by using a cached version of
     * it or building it on the fly
     *
     * @param string $classname
     * @return ReflectionComposite
     */
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
