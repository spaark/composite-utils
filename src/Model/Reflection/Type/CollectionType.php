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

namespace Spaark\CompositeUtils\Model\Reflection\Type;

/**
 * Represents a data type which is a collection of items
 *
 * @property-read AbstractType $of
 */
class CollectionType extends AbstractType
{
    /**
     * What is this a collection of
     *
     * @readable
     * @var AbstractType
     */
    protected $of;

    /**
     * Creates the CollectionType of the given subtype
     *
     * @param AbstractType $of The data type that this is a collection
     *     of
     */
    public function __construct(AbstractType $of)
    {
        $this->of = $of;
    }
}
