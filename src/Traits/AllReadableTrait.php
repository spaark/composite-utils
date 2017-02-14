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

use Spaark\CompositeUtils\Service\RawPropertyAccessor;

/**
 * Classes with this trait will allow all of their properties to be read
 * externally, but will not allow writing
 */
trait AllReadableTrait
{
    /**
     * @var RawPropertyAccessor
     */
    protected $accessor;

    /**
     * Returns the RawPropertyAccessor for this object, or constructs
     * one on the fly if one does not yet exist
     *
     * @return RawPropertyAccessor
     */
    protected function getPropertyAccessor()
    {
        if (!$this->accessor)
        {
            $this->accessor = new RawPropertyAccessor($this);
        }

        return $this->accessor;
    }

    /**
     * Gets the value of a property using the RawPropertyAccessor
     *
     * @param string $property The property to get
     * @return mixed The property value
     */
    public function __get(string $property)
    {
        return $this->getPropertyAccessor()->getRawValue($property);
    }
}
