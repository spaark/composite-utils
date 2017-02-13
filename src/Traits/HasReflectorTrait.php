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

use Spaark\CompositeUtils\Factory\Reflection\ReflectionCompositeFactory;
use Spaark\CompositeUtils\Model\Reflection\ReflectionComposite;

/**
 * Classes with this trait have a ReflectionComposite
 */
trait HasReflectorTrait
{
    /**
     * The reflection information for this composite
     *
     * @var ReflectionComposite
     */
    protected static $reflectionComposite;

    /**
     * Returns the ReflectionComposite for this object, or constructs
     * one on the fly if one does not yet exist, using a
     * ReflectionCompositeFactory
     *
     * @return ReflectionComposite
     */ 
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
}
