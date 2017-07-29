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

namespace Spaark\CompositeUtils\Traits;

use Spaark\CompositeUtils\Model\Reflection\Type\ObjectType;
use Spaark\CompositeUtils\Model\Generic\GenericContext;

/**
 * A generic class
 */
interface Generic
{
    /**
     * Get the object descriptor
     *
     * @return ObjectType
     */
    public function getObjectType() : ObjectType;

    /**
     * Get the generic context
     *
     * @return GenericContext
     */
    public function getGenericContext() : GenericContext;

    /**
     * Set the generic context
     *
     * @param GenericContext $genericContext
     */
    public function setGenericContext(GenericContext $genericContext)
        : void;
}
